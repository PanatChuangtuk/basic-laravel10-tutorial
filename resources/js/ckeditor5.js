import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Highlight from "@ckeditor/ckeditor5-highlight/src/highlight";

ClassicEditor.create(document.querySelector("#content")).catch((error) => {
    console.error(error);
});
