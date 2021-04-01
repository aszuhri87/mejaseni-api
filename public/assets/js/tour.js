$(function () {
    "use strict";
    var t = $("#tour");
    t.length &&
        t.on("click", function () {
            var t,
                e,
                a,
                n = new Shepherd.Tour({ defaultStepOptions: { classes: "shadow-md", scrollTo: !1, cancelIcon: { enabled: !0 } }, useModalOverlay: !0 });
            (e = "btn btn-sm btn-outline-primary"),
                (a = "btn btn-sm btn-primary btn-next"),
                (t = n).addStep({
                    title: "Dashboard",
                    text: "This is your Dashboard",
                    attachTo: { element: ".dashboard", on: "right" },
                    buttons: [
                        { action: t.cancel, classes: e, text: "Skip" },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Notification",
                    text: "This is a Notification",
                    attachTo: { element: ".notification", on: "right" },
                    buttons: [
                        { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Schedule",
                    text: "This is the Schedule",
                    attachTo: { element: ".schedule", on: "right" },
                    buttons: [
                        { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "My Class",
                    text: "This is the My Class",
                    attachTo: { element: ".my-class", on: "right" },
                    buttons: [
                        { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Materi",
                    text: "This is the Materi",
                    attachTo: { element: ".theory", on: "right" },
                    buttons: [
                        { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Exercise",
                    text: "This is the Exercise",
                    attachTo: { element: ".exercise", on: "right" },
                    buttons: [
                        { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Review",
                    text: "This is the Review",
                    attachTo: { element: ".review", on: "right" },
                    buttons: [
                        { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Buy New Package",
                    text: "This is the Buy New Package",
                    attachTo: { element: ".new-package", on: "right" },
                    buttons: [
                        { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Invoice List",
                    text: "This is the Invoice List",
                    attachTo: { element: ".invoice", on: "right" },
                    buttons: [
                        { text: "Back", classes: e, action: t.back },
                        { text: "Finish", classes: a, action: t.cancel },
                    ],
                }),
                t.start();
        });
});
