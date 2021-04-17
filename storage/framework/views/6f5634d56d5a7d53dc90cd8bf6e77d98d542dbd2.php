<footer class="footer <?php if(url()->current() == route('footer-dark')): ?> footer-dark <?php endif; ?> <?php if(url()->current() == route('footer-fixed')): ?> footer-fix <?php endif; ?>">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-6 footer-copyright">
            <p class="mb-0">Copyright <?php echo e(date('Y')); ?> © Xolo All rights reserved.</p>
         </div>
         <div class="col-md-6">
            <p class="pull-right mb-0">Hand crafted & made with &nbsp;<i class="fa fa-heart"></i></p>
         </div>
      </div>
   </div>
</footer><?php /**PATH C:\wamp64\www\hp\resources\views/layouts/light/footer.blade.php ENDPATH**/ ?>