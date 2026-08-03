new TomSelect('#select-tags', {
    plugins: ['remove_button'],

    render: {
        option: function (data, escape) {
            return `
            <div class="flex items-center gap-2">
                <span
                    style="
                        width:15px;
                        height:15px;
                        border-radius:5px;
                        background:${data.$option.dataset.color};
                    "
                ></span>

                ${escape(data.text)}
            </div>
        `;
        },

        item: function (data, escape) {
            return `
            <div class="flex items-center gap-2">
                <span
                    style="
                        width:12px;
                        height:12px;
                        border-radius:2px;
                        background:${data.$option.dataset.color};
                    "
                ></span>

                ${escape(data.text)}
            </div>
        `;
        }
    }
});
