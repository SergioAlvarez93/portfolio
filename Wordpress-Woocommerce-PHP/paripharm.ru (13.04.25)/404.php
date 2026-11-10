<?php get_header(); ?>
<main class="main">
    <section class="error-page">
        <div class="container">
            <div class="classic-h2"> Ошибка 404. Страница не существует.</div>
            <a href="<?= get_home_url(); ?>" class="classic-btn error-page__btn">На главную</a>
        </div>
    </section>
</main>
<?php get_footer(); ?>