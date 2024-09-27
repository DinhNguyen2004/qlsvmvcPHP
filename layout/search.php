<form action="/" method="GET">
    <label class="form-inline justify-content-end">Tìm kiếm: <input type="search" name="search" class="form-control"
            value="<?= $search ?? null ?>">
        <button class="btn btn-danger">Tìm</button>
        <!-- Xác định đang ở controller nào -->
        <?php if ($c != 'student'): ?>
            <input type="hidden" name="c" value="<?= $c ?>">
        <?php endif ?>
    </label>
</form>