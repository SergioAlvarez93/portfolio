<?php get_header(); ?>
<main class="main">
    <div class="container">
        <div class="top-wrap">
            <h1 class="classic-h1 errorpage-title">СТРАНИЦА НЕ СУЩЕСТВУЕТ</h1>
            <div class="errorpage-btns">
                <a href="javascript:history.back()" class="classic-btn errorpage-btns__back">НАЗАД</a>
                <a href="<?= get_home_url(); ?>" class="classic-btn errorpage-btns__main">НА ГЛАВНУЮ</a>
            </div>
        </div>
    </div>
</main>
<?php get_footer(); ?>