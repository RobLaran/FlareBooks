<section class="contact-section">
    <div class="container">
        <h1>Get in Touch</h1>
        <p
            style="max-width: 800px; margin: 20px auto 40px auto; font-size: 1.1rem; color: var(--secondary-text-light);">
            Have questions, feedback, or need support? Fill out the form below and we'll get back to you as soon as
            possible.
        </p>
        <div class="contact-content">
            <form action="<?= routeTo('/') ?>" class="contact-form">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject">

                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>

                <button> Send Message </button>
            </form>
        </div>
    </div>
</section>