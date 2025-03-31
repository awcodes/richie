import Image from "@tiptap/extension-image";

export default Image.extend({
    name: 'media',

    selectable: true,

    addAttributes() {
        return {
            src: {
                default: null,
            },
            alt: {
                default: '',
            },
            title: {
                default: null,
            },
            width: {
                default: null,
            },
            height: {
                default: null,
            },
            loading: {
                default: null,
            },
            sizes: {
                default: null,
            },
            srcset: {
                default: null,
            },
            alignment: {
                default: 'start',
                parseHTML: element => element.getAttribute('alignment'),
                renderHTML: attributes => {
                    let style;

                    switch(attributes.alignment) {
                        case 'center': style = 'margin-inline: auto'; break;
                        case 'end': style = 'margin-inline-start: auto'; break;
                        default: style = null;
                    }

                    return {
                        'alignment': attributes.alignment,
                        style,
                    }
                },
            },
            media: {
                default: null,
                parseHTML: element => element.getAttribute('data-media-id'),
                renderHTML: attributes => {
                    if (!attributes.media) {
                        return {}
                    }

                    return {
                        'data-media-id': attributes.media,
                    }
                },
            },
        };
    },

    addCommands() {
        return {
            setMedia: options => ({ commands }) => {
                const src = options?.url || options?.src;
                const imageTypes = ['jpg', 'jpeg', 'svg', 'png', 'webp', 'gif', 'avif', 'jxl', 'heic'];

                const regex = /.*\.([a-zA-Z]*)\??/;
                const match = regex.exec(src.toLowerCase());

                if (match !== null && imageTypes.includes(match[1])) {
                    commands.setImage({
                        ...options,
                        src: src,
                    })
                } else {
                    commands.setDocument(options)
                }
            },
            setDocument: options => ({ chain }) => {
                if (! [null, undefined].includes(options.coordinates?.from)) {
                    return chain().focus().extendMarkRange('link').setLink({ href: options.src }).insertContentAt({from: options.coordinates.from, to: options.coordinates.to}, {
                        type: this.name,
                        attrs: options,
                    }).run()
                }

                return chain().focus().extendMarkRange('link').setLink({ href: options.src }).insertContent(options?.link_text).run()
            },
            setImage: options => ({ chain, commands }) => {
                if (! [null, undefined].includes(options.coordinates?.from)) {
                    return chain().focus().insertContentAt(
                        {from: options.coordinates.from, to: options.coordinates.to},
                        {type: this.name, attrs: options}
                    ).run()
                }

                let currentChain = chain().focus().insertContent({
                    type: this.name,
                    attrs: options,
                })

                if (! commands.selectNodeForward()) {
                    currentChain.createParagraphNear()
                }

                return currentChain.run()
            },
        }
    },
});
