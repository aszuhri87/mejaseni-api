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
                    text: "Pada dashboard kamu dapat melihat informasi-informasi mengenai kelas yang kamu ikuti",
                    attachTo: { element: ".dashboard", on: "right" },
                    buttons: [
                        { action: t.cancel, classes: e, text: "Skip" },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),

                t.addStep({
                    title: "Buy New Package",
                    text: "Untuk mengikuti pelajaran kamu harus daftar kelas yang kamu ikuti pada menu buy new package, silakan 	pilih kelas sesuai keinginanmu.",
                    attachTo: { element: ".new-package", on: "right" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Cart",
                    text: "Setiap kelas yang kamu pilih akan masuk ke cart, silakan selesaikan pembayaran utuk mengikuti kelas yang kamu pilih.",
                    attachTo: { element: ".cart-tour", on: "bottom" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "My Class",
                    text: "Kelas yang sudah kamu beli akan ditampilkan di menu My Class.",
                    attachTo: { element: ".my-class", on: "right" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Schedule",
                    text: "pada menu ini kamu akan melihat jadwal dari setiap class yang kamu beli.",
                    attachTo: { element: ".schedule", on: "right" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Materi",
                    text: "Pada menu materi kamu akan mendapat materi dari coach di setiap kelas yang kamu ikuti.",
                    attachTo: { element: ".theory", on: "right" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Exercise",
                    text: "Setiap excercise yang diberikan oleh coach dari setiap class akan muncul di menu exercise",
                    attachTo: { element: ".exercise", on: "right" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Review",
                    text: "Kamu dapat memberi rating di stiap class yang sudah kamu ikuti.",
                    attachTo: { element: ".review", on: "right" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Notification",
                    text: "Pada notifikasi kamua akan mendapatkan informasi terbaru seputar kelas yang kamu ikuti.",
                    attachTo: { element: ".notification", on: "right" },
                    buttons: [
                        // { text: "Skip", classes: e, action: t.cancel },
                        { text: "Back", classes: e, action: t.back },
                        { text: "Next", classes: a, action: t.next },
                    ],
                }),
                t.addStep({
                    title: "Invoice List",
                    text: "Daftar Invoice akan muncul di menu berikut.",
                    attachTo: { element: ".invoice", on: "right" },
                    buttons: [
                        { text: "Back", classes: e, action: t.back },
                        { text: "Finish", classes: a, action: t.cancel },
                    ],
                }),
                t.start();
        });
});
