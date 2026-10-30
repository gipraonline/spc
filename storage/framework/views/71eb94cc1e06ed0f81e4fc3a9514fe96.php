<?php if($paginator->hasPages()): ?>
    <?php if (! $__env->hasRenderedOnce('06bb405d-7a19-48ec-97d1-f9fc5e2d3154')): $__env->markAsRenderedOnce('06bb405d-7a19-48ec-97d1-f9fc5e2d3154'); ?>
        <style>
            .hr-pager-list {
                display: flex;
                align-items: center;
                justify-content: center;
                flex-wrap: wrap;
                gap: 6px;
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .hr-pager-list li {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .hr-pager-list a,
            .hr-pager-list span {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 36px;
                height: 36px;
                padding: 0 10px;
                border: 1px solid #e5e7eb;
                border-radius: 999px;
                font-size: 13px;
                font-weight: 600;
                color: #0f4d2e;
                background: #fff;
                text-decoration: none;
                transition: background .15s;
            }
            .hr-pager-list a:hover {
                background: #f0f5f2;
            }
            .hr-pager-list .active span {
                background: #0f4d2e;
                border-color: #0f4d2e;
                color: #fff;
            }
            .hr-pager-list .disabled span {
                color: #b8bfc6;
                background: #f9fafb;
                cursor: not-allowed;
            }
        </style>
    <?php endif; ?>

    <nav role="navigation" aria-label="Pagination">
        <ul class="hr-pager-list">

            
            <?php if($paginator->onFirstPage()): ?>
                <li class="disabled"><span>&lsaquo;</span></li>
            <?php else: ?>
                <li><a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev">&lsaquo;</a></li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <li class="disabled"><span><?php echo e($element); ?></span></li>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="active"><span><?php echo e($page); ?></span></li>
                        <?php else: ?>
                            <li><a href="<?php echo e($url); ?>"><?php echo e($page); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li><a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next">&rsaquo;</a></li>
            <?php else: ?>
                <li class="disabled"><span>&rsaquo;</span></li>
            <?php endif; ?>

        </ul>
    </nav>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\spc_new\resources\views/vendor/pagination/hr.blade.php ENDPATH**/ ?>