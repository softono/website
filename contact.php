<?php include 'layout/header.php'; ?>
<div id="main-content" data-title="Contact">

    <section class="t-surface">
        <div class="mx-auto max-w-6xl px-4 py-16 sm:px-6">
            <p class="eyebrow">Contact</p>
            <h1 class="mt-2 text-4xl font-extrabold t-heading">Get in touch</h1>
        </div>
    </section>

    <section class="mx-auto max-w-2xl px-4 py-16 sm:px-6">
        <div class="card p-6 sm:p-8">
            <div class="ajax-response mb-4" role="status" aria-live="polite"></div>
            <form id="contact-form" action="contact-send" method="POST" class="space-y-5" novalidate>
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium">Name</label>
                    <input type="text" class="field" id="name" name="name" required>
                </div>
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium">Email</label>
                    <input type="email" class="field" id="email" name="email" required>
                </div>
                <div>
                    <label for="message" class="mb-1.5 block text-sm font-medium">Message</label>
                    <textarea class="field min-h-36" id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">Send message</button>
            </form>
        </div>
    </section>

    <script>
        documentReady(function () {
            $('#contact-form').validate({
                errorElement: 'p',
                errorClass: 'field-error',
                submitHandler: function (form) {
                    $.post($(form).attr('action'), $(form).serialize(), function (res) {
                        $('.ajax-response').text(res.message);
                        if (res.status == 1) form.reset();
                    }, 'json').fail(function () {
                        $('.ajax-response').text('Could not send message.');
                    });
                }
            });
        });
    </script>
</div>
<?php include 'layout/footer.php'; ?>
