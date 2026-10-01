    <?php load('helper/security'); ?>
    <?php component('hero', ['crumb' => $page['name'], 'eyebrow' => 'Contact', 'title' => 'Get in touch', 'lead' => 'Questions or ideas? Send a message and we will reply soon.']); ?>

    <section class="mx-auto max-w-2xl px-4 py-16 sm:px-6">
        <div class="card p-6 sm:p-8">
            <div class="ajax-response mb-4" role="status" aria-live="polite"></div>
            <form id="contact-form" action="contact-send" method="POST" class="space-y-5" novalidate>
                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium">Name</label>
                    <input type="text" class="field" id="name" name="name" autocomplete="name" required>
                </div>
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium">Email</label>
                    <input type="email" class="field" id="email" name="email" autocomplete="email" required>
                </div>
                <div>
                    <label for="phone" class="mb-1.5 block text-sm font-medium">Phone <span class="t-muted font-normal">(optional)</span></label>
                    <input type="tel" class="field" id="phone" name="phone" autocomplete="tel">
                </div>
                <div>
                    <label for="message" class="mb-1.5 block text-sm font-medium">Message</label>
                    <textarea class="field min-h-36" id="message" name="message" required></textarea>
                </div>
                <?= csrf_field(); ?>
                <div class="hp" aria-hidden="true">
                    <label for="website">Leave this field empty</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary w-full">Send message</button>
            </form>
        </div>
    </section>

    <script>
        documentReady(function () {
            var $resp = $('.ajax-response');
            function show(ok, msg) {
                $resp.attr('class', 'ajax-response mb-4 ' + (ok ? 'alert-success' : 'alert-error')).text(msg);
            }
            $('#contact-form').validate({
                errorElement: 'p',
                errorClass: 'field-error',
                highlight: function (el) { $(el).addClass('is-invalid').attr('aria-invalid', 'true'); },
                unhighlight: function (el) { $(el).removeClass('is-invalid').removeAttr('aria-invalid'); },
                submitHandler: function (form) {
                    var $btn = $(form).find('button[type=submit]').prop('disabled', true).text('Sending...');
                    $.post($(form).attr('action'), $(form).serialize(), function (res) {
                        show(res.status == 1, res.message);
                        if (res.status == 1) form.reset();
                    }, 'json').fail(function () {
                        show(false, 'Could not send message. Please try again.');
                    }).always(function () {
                        $btn.prop('disabled', false).text('Send message');
                    });
                }
            });
        });
    </script>
