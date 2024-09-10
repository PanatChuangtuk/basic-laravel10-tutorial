import "./bootstrap.js";
import "jquery-ui/dist/jquery-ui";
import { Dropzone } from "dropzone";
import $ from "jquery";

const errorMessage = $("#dzErrorMessage");
const placeHolder = $("#dzPlaceholder");
$(document).ready(function () {
    Dropzone.autoDiscover = false;
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

    myDropzone.on("addedfile", function (file) {
        errorMessage.hide();
        placeHolder.hide();

        file.tempId =
            "temp_" + file.name + "_" + file.size + "_" + file.lastModified;
        file.previewElement.setAttribute("data-id", file.tempId);

        updateAdditionalAreasDropzone();
    });

    myDropzone.on("error", function (file, response) {
        errorMessage.show().text(response);
        this.removeFile(file);

        updateAdditionalAreasDropzone();
    });

    myDropzone.on("sendingmultiple", function (file, xhr, formData) {
        const loadingDiv = $("#dzLoadingOverlay").html();

        $("#dzDropzone").append(loadingDiv);
    });

    function updateAdditionalAreasDropzone() {
        let additionalTemplate = $("#dzAdditionalTemplate").html();
        let filesCount = myDropzone.files.length;
        let additionalAreas = 0;

        $(".dz-additional-area").remove();

        additionalAreas = 5 - filesCount;

        for (let i = 0; i < additionalAreas; i++) {
            $(myDropzone.previewsContainer).append(additionalTemplate);
        }
    }

    $(document).on("click", ".dz-additional-area", function () {
        if (myDropzone.hiddenFileInput) {
            myDropzone.hiddenFileInput.click();
        }
    });

    $(document).on("click", ".dz-remove-button", function (event) {
        event.stopPropagation();

        const filePreview = $(this).closest(".dz-image-preview");
        const tempId = filePreview.data("id");
        const fileToRemove = myDropzone.file.find(function (file) {
            return file.tempId === tempId;
        });

        if (fileToRemove) {
            myDropzone.removeFile(fileToRemove);

            setTimeout(() => {
                if (myDropzone.files.length === 0) {
                    placeHolder.show();
                    $(".dz-additional-area").remove();
                } else {
                    updateAdditionalAreasDropzone();
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
            const files = myDropzone.files;
            const sortedFiles = [];

            $(".dz-image-preview").each(function () {
                const fileId = $(this).data("id");
                const file = myDropzone.files.find(
                    (file) => file.tempId === fileId
                );

                if (file) {
                    sortedFiles.push(file);
                }
            });

            myDropzone.files = sortedFiles;
        },
    });

    $(document).on(
        "mousedown",
        ".dz-image-preview:first-child",
        function (event) {
            return false;
        }
    );

    myDropzone.on("successmultiple", function (response) {
        const successMessage = $("#dzSuccessMessage").html();
        window.location.href = "/author/blog";
        myDropzone.removeAllFiles();
        $("#dzImageUploadForm").fadeOut(500);
        setTimeout(function () {
            $(".dz-loading-div").fadeOut();
            $(successMessage).insertBefore("#dzImageUploadForm").slideDown();
        }, 500);
        setTimeout(function () {
            $("#uploadedImagesSection").slideDown();
        }, 600);
    });

    $("#submit").on("click", function (event) {
        event.preventDefault(); // Stop the default action
        event.stopPropagation();
        myDropzone.processQueue();
    });
});

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
            return false;
        }
    );

    $("#submit").on("click", function (event) {
        myDropzoneUploads.processQueue();
        // window.location.reload();
    });
};
