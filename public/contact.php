<?php include __DIR__ . '/../layout/header.php'; ?>
<div id="main-content" data-title="Contact">

    <section class="t-surface hero">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <p class="eyebrow">Contact</p>
            <h1 class="mt-2 text-4xl font-extrabold t-heading">Get in touch</h1>
        </div>
    </section>

    <section class="mx-auto max-w-2xl px-4 py-16 sm:px-6">
        <div class="card p-6 sm:p-8">
            <div class="ajax-response" role="status" aria-live="polite"></div>
            <form id="contact-form" action="contact-send" method="POST" class="space-y-5" novalidate>
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium">Name</label>
                    <input type="text" class="field" id="name" name="name" autocomplete="name" maxlength="100" required>
                </div>
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium">Email</label>
                    <input type="email" class="field" id="email" name="email" autocomplete="email" maxlength="254" required>
                </div>
                <div>
                    <label for="message" class="mb-1.5 block text-sm font-medium">Message</label>
                    <textarea class="field min-h-36" id="message" name="message" maxlength="5000" required></textarea>
                </div>
                <div class="absolute -left-[9999px]" aria-hidden="true">
                    <label for="website">Leave this field empty</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary w-full">Send message</button>
            </form>
        </div>
    </section>

    <script>
        documentReady(function () {
            function show(msg, ok) {
                $('.ajax-response').attr('class', 'ajax-response form-alert ' + (ok ? 'form-alert-success' : 'form-alert-error')).text(msg);
            }
            $('#contact-form').validate({
                errorElement: 'p',
                errorClass: 'field-error',
                highlight: function (el, errorClass) { $(el).addClass(errorClass).attr('aria-invalid', 'true'); },
                unhighlight: function (el, errorClass) { $(el).removeClass(errorClass).removeAttr('aria-invalid'); },
                submitHandler: function (form) {
                    var $btn = $(form).find('[type=submit]').prop('disabled', true).text('Sending...');
                    $.post($(form).attr('action'), $(form).serialize(), function (res) {
                        show(res.message, res.status == 1);
                        if (res.status == 1) form.reset();
                    }, 'json').fail(function () {
                        show('Could not send message. Please try again or email us directly.', false);
                    }).always(function () {
                        $btn.prop('disabled', false).text('Send message');
                    });
                }
            });
        });
    </script>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
