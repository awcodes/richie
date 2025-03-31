import { mergeAttributes, Node } from '@tiptap/core'
import Suggestion from '@tiptap/suggestion'
import tippy from 'tippy.js'
import { PluginKey } from '@tiptap/pm/state'

export default Node.create({
    name: 'mentions',

    group: 'inline',

    inline: true,

    selectable: false,

    atom: true,

    addAttributes() {
        return {
            id: {
                default: null,
                parseHTML: element => element.getAttribute('data-id'),
                renderHTML: attributes => {
                    if (!attributes.id) {
                        return {}
                    }

                    return {
                        'data-id': attributes.id
                    }
                }
            },
        }
    },

    parseHTML() {
        return [
            {
                tag: `span[data-type='${this.name}']`
            }
        ]
    },

    renderHTML({ node, HTMLAttributes }) {
        return [
            'span',
            mergeAttributes(
                { 'data-type': this.name },
                HTMLAttributes
            ),
            `@${node.attrs.id}`,
        ]
    },

    renderText({ node }) {
        return `@${node.attrs.id} `
    },

    addKeyboardShortcuts() {
        return {
            Backspace: () =>
                this.editor.commands.command(({ tr, state }) => {
                    let isMention = false
                    const { selection } = state
                    const { empty, anchor } = selection

                    if (!empty) {
                        return false
                    }

                    state.doc.nodesBetween(anchor - 1, anchor, (node, pos) => {
                        if (node.type.name === this.name) {
                            isMention = true
                            tr.insertText(
                                '@',
                                pos,
                                pos + node.nodeSize
                            )

                            return false
                        }
                    })

                    return isMention
                })
        }
    },

    addProseMirrorPlugins() {
        return [
            Suggestion({
                editor: this.editor,
                char: '@',
                allowSpaces: true,
                items: ({ query }) => this.options.mentions.filter(item => item.toLowerCase().startsWith(query.toLowerCase())).slice(0, 5),
                pluginKey: new PluginKey('mentions'),
                command: ({ editor, range, props }) => {
                    range.to = editor.view.state.selection.$to.pos
                    const nodeAfter = editor.view.state.selection.$to.nodeAfter
                    const overrideSpace = nodeAfter?.text?.startsWith(' ')

                    if (overrideSpace) {
                        range.to += 1
                    }

                    editor
                        .chain()
                        .focus()
                        .insertContentAt(range, [
                            {
                                type: this.name,
                                attrs: props
                            },
                            {
                                type: 'text',
                                text: ' '
                            },
                        ])
                        .run()

                    editor.view.dom.ownerDocument.defaultView?.getSelection()?.collapseToEnd()
                },
                allow: ({ state, range }) => {
                    const $from = state.doc.resolve(range.from)
                    const type = state.schema.nodes[this.name]
                    return !!$from.parent.type.contentMatch.matchType(type)
                },
                render: () => {
                    let component
                    let popup

                    return {
                        onStart: (props) => {
                            if (!props.clientRect) {
                                return
                            }

                            const html = `
                                <div
                                    x-data="{
                                        items: ['${props.items.join('\', \'')}'],
                                        selectedIndex: 0,
                                        init: function () {
                                            this.$el.parentElement.addEventListener(
                                                'mentions-key-down',
                                                (event) => this.onKeyDown(event.detail),
                                            );

                                            this.$el.parentElement.addEventListener(
                                                'mentions-update-items',
                                                (event) => (this.items = event.detail),
                                            );
                                        },
                                        onKeyDown: function (event) {
                                            if (event.key === 'ArrowUp') {
                                                event.preventDefault();
                                                this.selectedIndex = ((this.selectedIndex + this.items.length) - 1) % this.items.length;

                                                return true;
                                            };

                                            if (event.key === 'ArrowDown') {
                                                event.preventDefault();
                                                this.selectedIndex = (this.selectedIndex + 1) % this.items.length;

                                                return true;
                                            };

                                            if (event.key === 'Enter') {
                                                event.preventDefault();
                                                this.selectItem(this.selectedIndex);

                                                return true;
                                            };

                                            return false;
                                        },
                                        selectItem: function (index) {
                                            const item = this.items[index];

                                            if (! item) {
                                                return;
                                            };

                                            $el.parentElement.dispatchEvent(new CustomEvent('mentions-select', { detail: { item } }));
                                        },
                                    }"
                                    class="richie-mentions"
                                >
                                    <template x-for="(item, index) in items" :key="index">
                                        <button
                                            type="button"
                                            x-text="item"
                                            x-on:click.prevent="selectItem(index)"
                                            :class="{'active': index === selectedIndex}"
                                            class="richie-mentions-item"
                                        ></button>
                                    </template>
                                </div>
                            `

                            component = document.createElement('div');
                            component.innerHTML = html;
                            component.addEventListener('mentions-select', (event) => {
                                props.command({ id: event.detail.item });
                            });

                            popup = tippy('body', {
                                getReferenceClientRect: props.clientRect,
                                content: component,
                                allowHTML: true,
                                showOnCreate: true,
                                interactive: true,
                                trigger: 'manual',
                                theme: 'richie-mentions',
                                placement: 'bottom-start',
                            });
                        },

                        onUpdate(props) {
                            if (!props.items.length) {
                                popup[0].hide();

                                return;
                            }

                            popup[0].show();

                            component.dispatchEvent(new CustomEvent('mentions-update-items', { detail: props.items }));
                        },

                        onKeyDown(props) {
                            if (
                                props.event.key === 'ArrowUp' ||
                                props.event.key === 'ArrowDown' ||
                                props.event.key === 'Enter'
                            ) {
                                component.dispatchEvent(new CustomEvent('mentions-key-down', { detail: props.event }));
                                return true;
                            }

                            return false;
                        },

                        onExit() {
                            popup[0].destroy();
                        },
                    }
                },
            })
        ]
    }
})
