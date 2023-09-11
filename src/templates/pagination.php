<nav class="mt-4">
    <ul class="pagination">
        <?php foreach ($pages as $page) : ?>
            <?php if ($page['disabled']) : ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><?= $page['title'] ?></a>
                </li>
            <?php elseif ($page['active']): ?>
                <li class="page-item active" aria-current="page">
                    <span class="page-link" ><?= $page['title'] ?></span>
                </li>
            <?php else: ?>
                <li class="page-item">
                <a class="page-link" href="<?= $page['url'] ?>"><?= $page['title'] ?></a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</nav>