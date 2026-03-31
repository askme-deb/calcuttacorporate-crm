tinymce.init({
    selector: ".editor",
    height: 500,
    plugins: [
        "advlist", "autolink", "link", "image", "lists", "charmap", "print", "preview",
        "hr", "anchor", "pagebreak", "searchreplace", "wordcount", "visualblocks",
        "visualchars", "code", "fullscreen", "insertdatetime", "media", "nonbreaking",
        "table", "emoticons", "template", "paste", "help", "autosave", "directionality",
        "importcss", "autosave", "save", "quickbars"
    ],
    toolbar: [
        "undo redo | styleselect | bold italic underline strikethrough | forecolor backcolor |",
        "alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |",
        "link image media table | code fullscreen preview print |",
        "charmap emoticons hr pagebreak insertdatetime anchor | removeformat"
    ].join(' '),
    menu: {
        favs: { title: "", items: "code visualaid | searchreplace | emoticons" }
    },
    menubar: "file edit view insert format tools table help",
    content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",

    setup: function (editor) {
        editor.on('init', function () {
            // Remove branding elements
            document.querySelectorAll('.tox-statusbar__branding').forEach(el => el.remove());
            document.querySelectorAll('.tox-tooltip').forEach(el => el.remove());
            document.querySelectorAll('.tox-promotion').forEach(el => el.remove());
        });
        
        // Also remove elements that might be added dynamically
        editor.on('focus blur click', function () {
            setTimeout(() => {
                document.querySelectorAll('.tox-tooltip').forEach(el => el.remove());
                document.querySelectorAll('.tox-promotion').forEach(el => el.remove());
            }, 100);
        });
    }
});