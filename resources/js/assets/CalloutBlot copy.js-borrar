const Block = Quill.import('blots/block');

class CalloutBlot extends Block {
    static blotName = 'callout';
    static tagName = 'div';
    static className = 'ql-callout';

    static allowedTypes = [
        'professional-tip',
        'warning',
        'note',
        'recommendation',
    ];

    static create(value) {
        const node = super.create();

        const type = this.allowedTypes.includes(value)
            ? value
            : 'note';

        node.setAttribute('data-callout', type);

        return node;
    }

    static formats(node) {
        return node.getAttribute('data-callout');
    }

    format(name, value) {
        if (name !== CalloutBlot.blotName) {
            super.format(name, value);
            return;
        }

        if (value && CalloutBlot.allowedTypes.includes(value)) {
            this.domNode.setAttribute('data-callout', value);
        } else {
            this.replaceWith('block');
        }
    }
}

Quill.register(CalloutBlot, true);