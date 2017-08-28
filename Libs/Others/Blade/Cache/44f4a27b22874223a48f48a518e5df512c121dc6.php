<?php $__env->startSection('title', 'Homepage'); ?>
<?php $__env->startSection('content'); ?>
<p>This is my body content.</p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('/Templates/_Layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>