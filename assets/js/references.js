jQuery(document).ready(function ($) {
    let page = 1;
    let currentIndustry = "";

    function loadReferences(industry, pageNum, append = false) {
        $.ajax({
            type: "POST",
            url: ajax_params.ajaxurl,
            data: {
                action: "filter_references",
                industry: industry,
                page: pageNum,
            },
            beforeSend: function () {
                $("#load-more").text("Laden...").prop("disabled", true);
            },
            success: function (response) {
                if (!append) {
                    $("#reference-list").html(response.html);
                } else {
                    $("#reference-list").append(response.html);
                }

                page = pageNum;
                $("#load-more")
                    .toggle(response.has_more)
                    .text("Mehr laden")
                    .prop("disabled", false);
            },
        });
    }

    $(".industry-button").click(function () {
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
        currentIndustry = $(this).data("industry");
        page = 1;
        loadReferences(currentIndustry, page);
    });

    $("#load-more").click(function () {
        loadReferences(currentIndustry, page + 1, true);
    });

    loadReferences("", 1);
});
