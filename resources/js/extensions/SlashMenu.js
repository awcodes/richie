import { Extension } from '@tiptap/core'
import Suggestion from '@tiptap/suggestion'
import tippy from 'tippy.js'
import { PluginKey } from '@tiptap/pm/state'

export default Extension.create({
    name: 'slashMenu',

    addOptions() {
        return {
            suggestions: {
                default: [],
            },
            appendTo: {
                default: document.body
            }
        }
    },

    addProseMirrorPlugins() {
        return [
            Suggestion({
                editor: this.editor,
                char: '/',
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
                        .deleteRange(range)
                        .run()

                    window.getSelection()?.collapseToEnd()

                    window.dispatchEvent(new CustomEvent('handle-suggestion', {
                        detail: {
                            item: props,
                            statePath: editor.commands.getStatePath(),
                            range: range,
                        }
                    }))
                },
                startOfLine: true,
                pluginKey: new PluginKey('slashExtension'),
                items: ({ query }) => {
                    return this.options.suggestions.filter(item => item.label.toLowerCase().includes(query.toLowerCase()))
                },
                render: () => {
                    let component
                    let popup

                    return {
                        onStart: props => {
                            if (!props.clientRect) {
                                return
                            }

                            const html = `
                                <div
                                    x-data='{
                                        items: ${JSON.stringify(props.items)},
                                        selectedIndex: 0,
                                        init: function () {
                                            this.$el.parentElement.addEventListener(
                                                "suggestions-key-down",
                                                (event) => this.onKeyDown(event.detail),
                                            );

                                            this.$el.parentElement.addEventListener(
                                                "suggestions-update-items",
                                                (event) => (this.items = event.detail),
                                            );
                                        },
                                        onKeyDown: function (event) {
                                            if (event.key === "ArrowUp") {
                                                event.preventDefault();
                                                this.selectedIndex = ((this.selectedIndex + this.items.length) - 1) % this.items.length;

                                                return true;
                                            };

                                            if (event.key === "ArrowDown") {
                                                event.preventDefault();
                                                this.selectedIndex = (this.selectedIndex + 1) % this.items.length;

                                                return true;
                                            };

                                            if (event.key === "Enter") {
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

                                            $el.parentElement.dispatchEvent(new CustomEvent("suggestions-select", { detail: { item } }));
                                        },
                                    }'
                                    class="richie-suggestions"
                                >
                                    <template x-for="(item, index) in items" :key="index">
                                        <button
                                            type="button"
                                            x-on:click.prevent="selectItem(index)"
                                            :class="{'active': index === selectedIndex}"
                                            class="richie-suggestion-item"
                                        >
                                            <span x-html="item.icon"></span>
                                            <span x-text="item.label"></span>
                                        </button>
                                    </template>
                                </div>
                            `

                            component = document.createElement('div')
                            component.innerHTML = html
                            component.addEventListener('suggestions-select', (event) => {
                                props.command({ ...event.detail.item })
                            });

                            popup = tippy('body', {
                                getReferenceClientRect: props.clientRect,
                                content: component,
                                allowHTML: true,
                                showOnCreate: true,
                                interactive: true,
                                trigger: 'manual',
                                theme: 'richie-suggestions',
                                placement: 'bottom-start',
                            })
                        },
                        onUpdate(props) {
                            if (!props.items.length) {
                                popup[0].hide()

                                return;
                            }

                            popup[0].show();

                            component.dispatchEvent(new CustomEvent('suggestions-update-items', { detail: props.items }))
                        },

                        onKeyDown(props) {
                            if (
                                props.event.key === 'ArrowUp' ||
                                props.event.key === 'ArrowDown' ||
                                props.event.key === 'Enter'
                            ) {
                                component.dispatchEvent(new CustomEvent('suggestions-key-down', { detail: props.event }));
                                return true;
                            }

                            return false;
                        },

                        onExit() {
                            popup[0].destroy()
                        },
                    }
                }
            }),
        ]
    },
})
