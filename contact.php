<?php include 'includes/header.php'; ?>

<h2 class="text-center mb-4">Contact Us</h2>

<div class="row justify-content-center">
  <div class="col-md-6">
    <form action="https://formspree.io/f/mzzakypr" method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">Your Name</label>
        <input type="text" class="form-control" name="name" id="name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Your Email</label>
        <input type="email" class="form-control" name="_replyto" id="email" required>
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea class="form-control" name="message" id="message" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-dark w-100">Send Message</button>
    </form>
  </div>
</div>

<a href="https://wa.me/94712345678" target="_blank" 
   class="whatsapp-float" title="Chat with us on WhatsApp">
   <img src="https://img.icons8.com/color/48/000000/whatsapp--v1.png" alt="Chat on WhatsApp">
</a>

<?php include 'includes/footer.php'; ?>