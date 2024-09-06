import "./bootstrap.js";
import "jquery-ui/dist/jquery-ui";
import { Dropzone } from "dropzone";
import $ from "jquery";

// ajax csrf setup
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
// set global variables
const errorMessage = $("#dzErrorMessage");
const placeHolder = $("#dzPlaceholder");

/**
 * ------------------------------------------------------------------------------------
 * DROPZONE SETUP
 * ------------------------------------------------------------------------------------
 */
$(document).ready(function () {
    Dropzone.autoDiscover = false;
    /**
     * Dropzone initial setup
     */
    const myDropzone = new Dropzone("#dzDropzone", {
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        url: "/author/insert",
        paramName: "images",
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 5,
        maxFiles: 5,
        thumbnailWidth: 800,
        thumbnailHeight: 500,
        previewTemplate: $("#dzImageTemplate").html(),
        previewsContainer: "#dzPreviews",
        acceptedFiles: "image/jpeg,image/png,application/pdf",
        maxFilesize: 2,
    });

    /**
     * If files dragged into dropzone
     */
    myDropzone.on("addedfile", function (file) {
        // hide placeholder and error messages
        errorMessage.hide();
        placeHolder.hide();

        // Generate a temporary identifier for each file (data-id)
        file.tempId =
            "temp_" + file.name + "_" + file.size + "_" + file.lastModified;
        file.previewElement.setAttribute("data-id", file.tempId);

        // add additional upload areas
        updateAdditionalAreasDropzone();
    });

    /**
     * If dropzone has validation errors
     */
    myDropzone.on("error", function (file, response) {
        errorMessage.show().text(response);
        this.removeFile(file);

        // add additional upload areas
        updateAdditionalAreasDropzone();
    });

    /**
     * On sending images to request
     */
    myDropzone.on("sendingmultiple", function (file, xhr, formData) {
        const loadingDiv = $("#dzLoadingOverlay").html();

        // show loading div
        $("#dzDropzone").append(loadingDiv);

        // attach csrf token
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        formData.append("title", $("#title").val());
        formData.append("content", $("#content").val());
    });

    /**
     * Adjust additional upload areas
     */
    function updateAdditionalAreasDropzone() {
        let additionalTemplate = $("#dzAdditionalTemplate").html();
        let filesCount = myDropzone.files.length;
        let additionalAreas = 0;

        // remove all additional areas first
        $(".dz-additional-area").remove();

        // count how many additional areas needed
        additionalAreas = 5 - filesCount;

        // render the needed additional areas
        for (let i = 0; i < additionalAreas; i++) {
            $(myDropzone.previewsContainer).append(additionalTemplate);
        }
    }

    /**
     * If an additional area is clicked
     */
    $(document).on("click", ".dz-additional-area", function () {
        if (myDropzone.hiddenFileInput) {
            // open the file browser
            myDropzone.hiddenFileInput.click();
        }
    });

    /**
     * Remove image function
     */
    $(document).on("click", ".dz-remove-button", function (event) {
        event.preventDefault();
        event.stopPropagation();

        // find the corresponding dropzone object
        const filePreview = $(this).closest(".dz-image-preview");
        const tempId = filePreview.data("id");
        const fileToRemove = myDropzone.file.find(function (file) {
            return file.tempId === tempId;
        });

        if (fileToRemove) {
            // remove the file
            myDropzone.removeFile(fileToRemove);

            // delay the execution of the layout adjustment
            setTimeout(() => {
                // if there are no more files, show the upload prompt again
                if (myDropzone.files.length === 0) {
                    placeHolder.show();
                    $(".dz-additional-area").remove();
                } else {
                    // update the additional areas in case the count needs adjusting
                    updateAdditionalAreasDropzone();
                }
            }, 10);
        }

        // hide error messages
        errorMessage.hide();
    });

    /**
     * ------------------------------------------------------------------------------------
     * SORTABLE SETUP
     * ------------------------------------------------------------------------------------
     */

    /**
     * Sortable initial setup
     */
    $("#dzPreviews").sortable({
        items: ".dz-image-preview",
        cancel: ".dz-image-preview:first-child",
        placeholder: "sortable-placeholder",
        tolerance: "pointer",
        start: function (event, ui) {
            // nitial placeholder setup to match the dragged item's size
            ui.placeholder.width(ui.item.width()).height(ui.item.height());
        },
        change: function (event, ui) {
            var isPlaceholderFirst = ui.placeholder.index() === 0;

            if (isPlaceholderFirst) {
                ui.placeholder.addClass("cover-placeholder");
            } else {
                ui.placeholder.removeClass("cover-placeholder");
            }
        },
        stop: function () {
            // update the files array based on new order
            const files = myDropzone.files;
            const sortedFiles = [];

            $(".dz-image-preview").each(function () {
                // find the file unique data-id
                const fileId = $(this).data("id");
                const file = myDropzone.files.find(
                    (file) => file.tempId === fileId
                );

                // if file found, push to order array
                if (file) {
                    sortedFiles.push(file);
                }
            });

            myDropzone.files = sortedFiles;
        },
    });

    /**
     * Prevent the first cover image from being dragged
     */
    $(document).on(
        "mousedown",
        ".dz-image-preview:first-child",
        function (event) {
            event.preventDefault();
            return false;
        }
    );

    /**
     * ------------------------------------------------------------------------------------
     * SUBMIT IMAGES
     * ------------------------------------------------------------------------------------
     */

    /**
     * On successful upload
     */
    myDropzone.on("successmultiple", function (response) {
        const successMessage = $("#dzSuccessMessage").html();
        window.location.href = "/author/blog";
        // remove files from dropzone & hide form
        myDropzone.removeAllFiles();
        $("#dzImageUploadForm").fadeOut(500);

        // hide loading div & show success message
        setTimeout(function () {
            $(".dz-loading-div").fadeOut();
            $(successMessage).insertBefore("#dzImageUploadForm").slideDown();
        }, 500);

        // show display uploaded images section
        setTimeout(function () {
            $("#uploadedImagesSection").slideDown();
        }, 600);
    });

    /**
     * Submit images upload
     */
    $("#dzSubmitButton").on("click", function (event) {
        event.preventDefault();
        if ($("#title").val() === "" || $("#content").val() === "") {
            alert("Title and Content are required.");
        } else myDropzone.getQueuedFiles().length > 0;
        {
            myDropzone.processQueue();
        }
    });
});
//     /**
//      * ------------------------------------------------------------------------------------
//      * DISPLAY PREVIEWS
//      * ------------------------------------------------------------------------------------
//      */
// });
/**
 * ------------------------------------------------------------------------------------
 * PROJECT SETUP
 * ------------------------------------------------------------------------------------
 */

/**
 * ------------------------------------------------------------------------------------
 * DROPZONE SETUP
 * ------------------------------------------------------------------------------------
 */
/**
 * Dropzone initial setup
 */

window.onload = function () {
    Dropzone.autoDiscover = false;

    const myDropzoneUploads = new Dropzone("#dzDropzoneUploads", {
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        url: updateUrl,
        paramName: "images",
        method: "POST",
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 5,
        maxFiles: 5,
        thumbnailWidth: 800,
        thumbnailHeight: 500,
        previewTemplate: $("#dzImageTemplate").html(),
        previewsContainer: "#dzPreviews",
        acceptedFiles: "image/jpeg,image/png,application/pdf",
        maxFilesize: 2,
    });

    myDropzoneUploads.on("addedfile", function (file) {
        errorMessage.hide();
        placeHolder.hide();
        file.tempId =
            "temp_" + file.name + "_" + file.size + "_" + file.lastModified;
        file.previewElement.setAttribute("data-id", file.tempId);
        updateAdditionalAreasUploads();
    });

    myDropzoneUploads.on("error", function (file, response) {
        errorMessage.show().text(response);
        this.removeFile(file);
        updateAdditionalAreasUploads();
    });

    myDropzoneUploads.on("sendingmultiple", function (file, xhr, formData) {
        const loadingDiv = $("#dzLoadingOverlay").html();
        $("#dzDropzoneUploads").append(loadingDiv);

        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        formData.append("title", $("#title").val());
        formData.append("content", $("#content").val());
    });

    function updateAdditionalAreasUploads() {
        let additionalTemplate = $("#dzAdditionalTemplate").html();
        let filesCount = myDropzoneUploads.files.length;
        let additionalAreas = 0;

        $(".dz-additional-area").remove();
        additionalAreas = 5 - filesCount;

        for (let i = 0; i < additionalAreas; i++) {
            $(myDropzoneUploads.previewsContainer).append(additionalTemplate);
        }
    }

    $(document).on("click", ".dz-additional-area", function () {
        if (myDropzoneUploads.hiddenFileInput) {
            myDropzoneUploads.hiddenFileInput.click();
        }
    });

    $(document).on("click", ".dz-remove-button", function (event) {
        event.preventDefault();
        event.stopPropagation();
        const filePreview = $(this).closest(".dz-image-preview");
        const tempId = filePreview.data("id");
        const fileToRemove = myDropzoneUploads.files.find(
            (file) => file.tempId === tempId
        );
        if (fileToRemove) {
            myDropzoneUploads.removeFile(fileToRemove);
            setTimeout(() => {
                if (myDropzoneUploads.files.length === 0) {
                    placeHolder.show();
                    $(".dz-additional-area").remove();
                } else {
                    updateAdditionalAreasUploads();
                }
            }, 10);
        }
        errorMessage.hide();
    });

    $("#dzPreviews").sortable({
        items: ".dz-image-preview",
        cancel: ".dz-image-preview:first-child",
        placeholder: "sortable-placeholder",
        tolerance: "pointer",
        start: function (event, ui) {
            ui.placeholder.width(ui.item.width()).height(ui.item.height());
        },
        change: function (event, ui) {
            var isPlaceholderFirst = ui.placeholder.index() === 0;
            if (isPlaceholderFirst) {
                ui.placeholder.addClass("cover-placeholder");
            } else {
                ui.placeholder.removeClass("cover-placeholder");
            }
        },
        stop: function () {
            const files = myDropzoneUploads.files;
            const sortedFiles = [];
            $(".dz-image-preview").each(function () {
                const fileId = $(this).data("id");
                const file = myDropzoneUploads.files.find(
                    (file) => file.tempId === fileId
                );
                if (file) {
                    sortedFiles.push(file);
                }
            });
            myDropzoneUploads.files = sortedFiles;
        },
    });

    $(document).on(
        "mousedown",
        ".dz-image-preview:first-child",
        function (event) {
            event.preventDefault();
            return false;
        }
    );

    $("#dzSubmitButton").on("click", function (event) {
        event.preventDefault();
        if ($("#title").val() === "" || $("#content").val() === "") {
            alert("Title and Content are required.");
        } else if (myDropzoneUploads.getQueuedFiles().length > 0) {
            myDropzoneUploads.processQueue();
            // window.location.reload();
        }
    });
};
