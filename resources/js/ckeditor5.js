import { ClassicEditor, Font, FontFamily, Clipboard } from "ckeditor5";
import { SlashCommand } from "ckeditor5-premium-features";

ClassicEditor.create(document.querySelector("#content"))
    .then((editor) => {
        console.log("Editor is ready!", editor);
    })
    .catch((error) => {
        console.error("Error:", error);
    });
