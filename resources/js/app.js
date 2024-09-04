/**
 * ------------------------------------------------------------------------------------
 * PROJECT SETUP
 * ------------------------------------------------------------------------------------
 */

// imports
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

Dropzone.autoDiscover = false;

/**
 * Dropzone initial setup
 */
const myDropzone = new Dropzone("#dzDropzone", {
    url: "/author/insert",
    paramName: "file[]", // ชื่อพารามิเตอร์สำหรับไฟล์
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 2,
    maxFiles: 2,
    thumbnailWidth: 800,
    thumbnailHeight: 500,
    previewTemplate: $("#dzImageTemplate").html(),
    previewsContainer: "#dzPreviews",
    acceptedFiles: "image/jpeg,image/png,application/pdf",
    maxFilesize: 2, // ขนาดไฟล์สูงสุดใน MB
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
    updateAdditionalAreas();
});

/**
 * If dropzone has validation errors
 */
myDropzone.on("error", function (file, response) {
    errorMessage.show().text(response);
    this.removeFile(file);

    // add additional upload areas
    updateAdditionalAreas();
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
function updateAdditionalAreas() {
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
    const fileToRemove = myDropzone.files.find(function (file) {
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
                updateAdditionalAreas();
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

        // show the first cover image's placeholder if dragged into it
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
$(document).on("mousedown", ".dz-image-preview:first-child", function (event) {
    event.preventDefault();
    return false;
});

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
        getUploadedImages();
        $("#uploadedImagesSection").slideDown();
    }, 600);
});

/**
 * Submit images upload
 */
$("#dzSubmitButton").on("click", function (event) {
    event.preventDefault();

    // show error messages if not have enough images
    if (myDropzone.files.length === 0) {
        errorMessage.show().text("You have to upload at least 1 image.");
    } else {
        // process the queue
        myDropzone.processQueue();
    }
});

/**
 * ------------------------------------------------------------------------------------
 * DISPLAY PREVIEWS
 * ------------------------------------------------------------------------------------
 */
