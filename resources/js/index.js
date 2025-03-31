import {Editor} from '@tiptap/core'
import StatePath from './extensions/StatePath.js'
import Link from './extensions/Link.js'
import Classes from './extensions/Classes.js'
import Ids from './extensions/Ids.js'
import MergeTag from './extensions/MergeTag.js'
import DragAndDrop from './extensions/DragAndDrop.js'
import {Underline} from '@tiptap/extension-underline'
import {Subscript} from '@tiptap/extension-subscript'
import {Superscript} from '@tiptap/extension-superscript'
import TextAlign from './extensions/TextAlign.js'
import {Table} from '@tiptap/extension-table'
import {TableRow} from '@tiptap/extension-table-row'
import {TableHeader} from '@tiptap/extension-table-header'
import {TableCell} from '@tiptap/extension-table-cell'
import {TextStyle} from '@tiptap/extension-text-style'
import Grid from './extensions/Grid/Grid.js'
import GridColumn from './extensions/Grid/GridColumn.js'
import Details from './extensions/Details/Details.js'
import DetailsContent from './extensions/Details/DetailsContent.js'
import DetailsSummary from './extensions/Details/DetailsSummary.js'
import Media from './extensions/Media.js'
import Block from './extensions/Block.js'
import SlashMenu from './extensions/SlashMenu.js'
import {Placeholder} from "@tiptap/extension-placeholder";
import {Document} from "@tiptap/extension-document";
import {Text} from "@tiptap/extension-text";
import {Paragraph} from "@tiptap/extension-paragraph";
import {Dropcursor} from "@tiptap/extension-dropcursor";
import {Gapcursor} from "@tiptap/extension-gapcursor";
import {HardBreak} from "@tiptap/extension-hard-break";
import {Heading} from "@tiptap/extension-heading";
import {Bold} from "@tiptap/extension-bold";
import {Italic} from "@tiptap/extension-italic";
import {Strike} from "@tiptap/extension-strike";
import {BulletList} from "@tiptap/extension-bullet-list";
import {OrderedList} from "@tiptap/extension-ordered-list";
import {Code} from "@tiptap/extension-code";
import {ListItem} from "@tiptap/extension-list-item";
import {History} from "@tiptap/extension-history";
import Lead from "./extensions/Lead.js";
import Small from "./extensions/Small.js";
import {Blockquote} from "@tiptap/extension-blockquote";
import CustomCommands from "./extensions/CustomCommands.js";
import {HorizontalRule} from "@tiptap/extension-horizontal-rule";
import CodeBlockLowlight from './extensions/CodeBlock.js'
import lowlight from "./extensions/Lowlight.js";
import {Color} from "@tiptap/extension-color";
import {Highlight} from "@tiptap/extension-highlight";
import Mentions from './extensions/Mentions.js';
import Embed from './extensions/Embed.js';
import { Selection } from '@tiptap/pm/state'

window.richieExtensions = [];

export default function richie({
    key,
    livewireId,
    state,
    statePath,
    disabled = false,
    placeholder = null,
    mergeTags = [],
    suggestions = [],
    mentions = [],
    allowedExtensions = [],
    headingLevels = [1,2,3],
    customDocument = null,
    nodePlaceholders = [],
    showOnlyCurrentPlaceholder = true,
    enableInputRules = true,
    enablePasteRules = true,
    debounce = null,
    linkProtocols = [],
}) {
    let editor = null;

    return {
        updatedAt: Date.now(),
        state: state,
        statePath: statePath,
        fullscreen: false,
        viewport: 'desktop',
        isFocused: false,
        sidebarOpen: true,
        wordCount: 0,
        enableInputRules: enableInputRules,
        enablePasteRules: enablePasteRules,
        shouldUpdateState: true,
        editorSelection: { type: 'text', anchor: 0, head: 0 },
        isUpdatingBlock: false,
        isInsertingBlock: false,
        timeOut: null,
        init() {
            editor = new Editor({
                element: this.$refs.element,
                extensions: this.getExtensions(),
                content: this.state,
                enableInputRules: this.enableInputRules,
                enablePasteRules: this.enablePasteRules,
                editorProps: {
                    handlePaste(view, event, slice) {
                        slice.content.descendants(node => {
                            if (node.type.name === 'richieBlock') {
                                const parser = new DOMParser()
                                const doc = parser.parseFromString(node.attrs.view, 'text/html')
                                node.attrs.view = doc.documentElement.textContent
                            }
                        })
                    }
                },
            })

            editor.on('create', ({editor}) => {
                this.wordCount = this.getWordCount(editor)
                this.updatedAt = Date.now()
            })

            editor.on('update', ({editor}) => {
                this.updatedAt = Date.now()

                clearTimeout(this.timeOut)

                this.timeOut = setTimeout(() => {
                    this.state = editor.getJSON()

                    this.shouldUpdateState = false

                    this.wordCount = this.getWordCount(editor)
                }, debounce ?? 0);
            })

            editor.on('selectionUpdate', ({editor, transaction}) => {
                this.updatedAt = Date.now()
                this.editorSelection = transaction.selection.toJSON()

                window.dispatchEvent(
                    new CustomEvent(`selection-update`),
                )
            })

            editor.on('focus', ({editor}) => {
                this.isFocused = true
                this.updatedAt = Date.now()
            })

            let sortableEl = this.$el.parentElement.closest("[x-sortable]");
            if (sortableEl) {
                window.Sortable.utils.on(sortableEl, "start", () => {
                    sortableEl.classList.add('sorting')
                });

                window.Sortable.utils.on(sortableEl, "end", () => {
                    sortableEl.classList.remove('sorting')
                });
            }

            this.$watch('isFocused', (value) => {
                if (value === false) {
                    this.blurEditor()
                }
            })

            this.$watch('state', () => {
                if (! this.shouldUpdateState) {
                    this.shouldUpdateState = true

                    return
                }

                if (this.state === undefined) {
                    return
                }

                editor.chain().setContent(this.state).run()
            });

            window.addEventListener('run-richie-commands', (event) => {
                if (event.detail.livewireId !== livewireId) {
                    return
                }

                if (event.detail.key !== key) {
                    return
                }

                this.runEditorCommands(event.detail)
            })

            window.dispatchEvent(
                new CustomEvent(`richie-component-${livewireId}-${key}-loaded`),
            )
        },
        editor() {
            return editor;
        },
        getWordCount(editor) {
            return editor.getText().trim().split(' ').filter(word => word !== '').length
        },
        handleCommand(command, args = null) {
            editor.chain().focus()[command](args).run()
        },
        handleSuggestion(event) {
            if (event.detail.statePath === editor.commands.getStatePath()) {
                this.$nextTick(() => {
                    if (event.detail.item.actionType === "alpine") {
                        this.handleCommand(event.detail.item.commandName, event.detail.item.commandAttributes)
                    } else {
                        this.$wire.mountFormComponentAction(
                            this.statePath,
                            event.detail.item.name,
                            { ...this.editor().getAttributes(event.detail.item.name), editorSelection: this.editorSelection },
                            this.key
                        )
                    }
                })
            }
        },
        isActive(name, attrs) {
            return editor.isActive(name, attrs)
        },
        toggleFullscreen() {
            this.fullscreen = !this.fullscreen

            editor.commands.focus()

            if (! this.fullscreen) {
                this.viewport = 'desktop'
            }

            this.updatedAt = Date.now()
        },
        toggleViewport(viewport) {
            this.viewport = viewport

            this.updatedAt = Date.now()
        },
        toggleSidebar() {
            this.sidebarOpen = ! this.sidebarOpen
            editor.commands.focus()
            this.updatedAt = Date.now()
        },
        focusEditor(event) {
            if (event.detail.statePath === this.editor().commands.getStatePath()) {
                setTimeout(() => this.editor().commands.focus(), 200)
                this.updatedAt = Date.now()
            }
        },
        blurEditor() {
            const tippy = this.$el.querySelectorAll('[data-tippy-content]')
            this.$el.querySelectorAll('.is-active')?.forEach((item) => item.classList.remove('is-active'))

            if (tippy) {
                tippy.forEach((item) => item.destroy())
            }

            this.isFocused = false
            this.updatedAt = Date.now()
        },
        insertMergeTag(event) {
            editor.commands.insertMergeTag({
                tag: event.detail.tag,
                coordinates: event.detail.coordinates,
            });

            if (! editor.isFocused) {
                editor.commands.focus();
            }

            this.updatedAt = Date.now()
        },
        getExtensions() {
            const coreExtensions = [
                Block,
                Classes,
                CustomCommands,
                customDocument ? Document.extend({
                    content: customDocument
                }) : Document,
                DragAndDrop,
                Dropcursor,
                Gapcursor,
                HardBreak,
                History,
                Ids,
                Paragraph,
                StatePath.configure({
                    statePath: statePath
                }),
                Text,
                TextStyle,
            ];

            if ((placeholder || nodePlaceholders) && (!disabled)) {
               coreExtensions.push(Placeholder.configure({
                   showOnlyCurrent: showOnlyCurrentPlaceholder,
                   placeholder: ({ node }) => {
                       const nodeSpecificPlaceholder = nodePlaceholders?.[node.type.name];
                       return nodeSpecificPlaceholder || placeholder || '';
                   },
               }))
            }

            if (suggestions.length) {
                coreExtensions.push(SlashMenu.configure({
                    suggestions: suggestions,
                    appendTo: this.$refs.element
                }))
            }

            if (mergeTags.length) {
                coreExtensions.push(MergeTag.configure({mergeTags}))
            }

            if (mentions.length) {
                coreExtensions.push(Mentions.configure({mentions}))
            }

            const defaultExtensions = {
                'Blockquote': Blockquote,
                'Bold': Bold,
                'BulletList': [BulletList, ListItem],
                'Code': Code,
                'CodeBlock': CodeBlockLowlight.configure({lowlight}),
                'Color': Color,
                'Details': [Details, DetailsContent, DetailsSummary],
                'Embed': Embed,
                'Grid': [Grid, GridColumn],
                'Heading': Heading.configure({ levels: headingLevels }),
                'Highlight': Highlight,
                'HorizontalRule': HorizontalRule,
                'Italic': Italic,
                'Lead': Lead,
                'Link': Link.configure({ protocols: linkProtocols}),
                'Media': Media,
                'OrderedList': [OrderedList, ListItem],
                'Small': Small,
                'Strike': Strike,
                'Subscript': Subscript,
                'Superscript': Superscript,
                'Table': [Table.configure({ resizable: true, }), TableRow, TableHeader, TableCell],
                'TextAlign': TextAlign,
                'Underline': Underline,
            };

            const extensionsMap = {
                ...defaultExtensions,
                ...window?.richieExtensions || {}
            }

            Object.keys(extensionsMap).forEach((extension) => {
                if (! Object.values(allowedExtensions).includes(extension)) {
                    delete extensionsMap[extension]
                }
            })

            return Array.from(new Set([
                ...coreExtensions,
                ...Object.values(extensionsMap).flat(),
            ]));
        },
        setEditorSelection: function (selection) {
            if (!selection || editor.isEmpty) {
                return
            }

            const contentSize = editor.state.doc.content.size

            if (selection.anchor > contentSize) {
                selection.anchor = selection.head = contentSize
            }

            this.editorSelection = selection

            if (contentSize === this.editorSelection.anchor) {
                editor.commands.insertContentAt(this.editorSelection.anchor, { type: 'paragraph' })
            }

            editor
                .chain()
                .command(({ tr }) => {
                    tr.setSelection(
                        Selection.fromJSON(
                            editor.state.doc,
                            this.editorSelection,
                        ),
                    )

                    return true
                })
                .run()
        },
        runEditorCommands: function ({ commands, editorSelection }) {
            this.setEditorSelection(editorSelection)

            let commandChain = editor.chain().focus()

            if (this.isUpdatingBlock) {
                commandChain.setMeta('isUpdatingBlock', true)
                this.isUpdatingBlock = false
            }

            if (this.isInsertingBlock) {
                commandChain.setMeta('isInsertingBlock', true)
                this.isInsertingBlock = false
            }

            commands.forEach(
                (command) =>
                    (commandChain = commandChain[command.name](
                        ...(command.arguments ?? []),
                    )),
            )

            commandChain.run()
        },
        handleBlockUpdate: function (identifier) {
            if (! this.isUpdatingBlock) {
                this.isUpdatingBlock = true
            }

            const data = editor.getAttributes('richieBlock')

            this.$wire.mountFormComponentAction(
                this.statePath,
                identifier,
                { ...data.values, editorSelection: this.editorSelection },
                this.key
            )
        },
        handleBlockDrop: function (event) {
            if (! this.isInsertingBlock) {
                this.isInsertingBlock = true
            }

            let pos = event.detail.coordinates.pos

            this.$wire.mountFormComponentAction(
                this.statePath,
                event.detail.name,
                { editorSelection: { type: 'node', anchor: pos, head: pos } },
                this.key
            )
        }
    }
}
