import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight'
import lowlight from "./Lowlight.js";

export default CodeBlockLowlight.extend({
    addNodeView() {
        return ({ node, editor, extension, getPos }) => {
            const { view } = editor

            // Create the container for the code block and dropdown
            const container = document.createElement('div');
            const controls = document.createElement('div');
            container.classList.add('code-block-container');
            controls.classList.add('richie-block-controls');

            // Create the dropdown for language selection
            const select = document.createElement('select');
            select.classList.add('language-select');
            select.contentEditable = 'false';

            lowlight.listLanguages().forEach(lang => {
                const option = document.createElement('option');
                option.value = lang;
                option.textContent = lang;
                option.selected = lang === node.attrs.language;
                select.appendChild(option);
            });

            // Handle language change
            select.addEventListener('change', (event) => {
                if (typeof getPos === 'function') {
                    view.dispatch(view.state.tr.setNodeMarkup(getPos(), undefined,{
                        language: event.target.value
                    }))
                }
            });

            // Create the pre and code elements for the code block
            const pre = document.createElement('pre');
            const code = document.createElement('code');
            code.textContent = node.textContent;
            code.classList.add(`${extension.options.languageClassPrefix}${node.attrs.language}`)
            pre.appendChild(code);

            // Apply syntax highlighting
            if (node.attrs.language) {
                const highlighted = lowlight.highlight(node.attrs.language, node.textContent);
                code.innerHTML = highlighted.value;
            }

            // Append the select and pre to the container
            controls.appendChild(select);
            container.appendChild(controls);
            container.appendChild(pre);

            return {
                dom: container,
                contentDOM: code,
            };
        };
    },
})
