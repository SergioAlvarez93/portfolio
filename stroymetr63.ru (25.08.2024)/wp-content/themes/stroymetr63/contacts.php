<?php
/*
Template Name: Контакты
*/
?>
<?php get_header(); ?>
<main class="main">
  <section class="information__bg">
    <div class="basic-container">
      <div class="information">
        <div class="information__wrap">
          <div class="information-fbox">
            <div class="information-fbox__general">
              <div class="information-fbox__general-item">
                <h2 class="basic-title footer-header">Контакты</h2>
                <div class="information-fbox__general-contacts-wrap">
                  <?php if ($contact = get_field('working_time', 'options')) { ?>
                    <div class="footer-contacts">
                      <div class="footer-img__wrap">
                        <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/clock.svg" ?> alt="" />
                      </div>
                      <div class="footer-contacts__content">
                        <?php echo $contact; ?>
                      </div>
                    </div>
                  <?php } ?>
                  <?php if ($contact = get_field('adress', 'options')) { ?>
                    <div class="footer-contacts">
                      <div class="footer-img__wrap">
                        <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/point.svg" ?> alt="" />
                      </div>
                      <div class="footer-contacts__content">
                        <?php echo $contact; ?>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
              <div class="information-fbox__general-item">
                <h2 class="basic-title footer-header">Мессенджеры</h2>
                <div class="information-fbox__general-contacts-wrap"></div>
                <div class="footer-social">
                  <?php if ($contact = get_field('whatsapp', 'options')) { ?>
                    <a href="<?php echo $contact; ?>" class="footer-img__wrap" target="_blank">
                      <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/whatsapp.svg" ?> alt="" />
                    </a>
                  <?php } ?>
                  <?php if ($contact = get_field('telegram', 'options')) { ?>
                    <a href="<?php echo $contact; ?>" class="footer-img__wrap" target="_blank">
                      <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/telegram.svg" ?> alt="" />
                    </a>
                  <?php } ?>
                </div>
              </div>
              <?php if ($contact = get_field('adress', 'options')) { ?>
                <div class="information-fbox__general-item">
                  <h2 class="basic-title footer-header">Адрес</h2>
                  <div class="information-fbox__general-contacts-wrap">
                    <div class="footer-contacts">
                      <div class="footer-img__wrap">
                        <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/point.svg" ?> alt="" />
                      </div>
                      <div class="footer-contacts__content">
                        <?php echo $contact; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              <?php if ($contact = get_field('working_time', 'options')) { ?>
                <div class="information-fbox__general-item">
                  <h2 class="basic-title footer-header">Режим работы</h2>
                  <div class="information-fbox__general-contacts-wrap">
                    <div class="footer-contacts">
                      <div class="footer-img__wrap">
                        <img class="footer-img" src=<?= get_template_directory_uri() . "/img/footer/clock.svg" ?> alt="" />
                      </div>
                      <div class="footer-contacts__content">
                        <?php echo $contact; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?>
              <?php if ($contact = get_field('contacts_info', 'options')) { ?>
                <div class="information-fbox__general-item">
                  <h2 class="basic-title footer-header">
                    Контактная информация
                  </h2>
                  <!-- prettier-ignore -->
                  <div class="information-fbox__general-doc"><?php echo $contact; ?></div>
                </div>
              <?php } ?>
            </div>
            <?php if ($value = get_field('contacts_img')) { ?>
              <div class="information-fbox__img-wrap">
                <img src=<?= $value; ?> alt="" class="information-fbox__img" />
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php if ($value = get_field('contacts_map')) { ?>
    <section class="basic-container">
      <div class="map__wrap">
        <?= $value; ?>
      </div>
    </section>
  <?php } ?>
  <!-- <section class="calc__bg">
    <div class="calc__container">
      <div class="calc">
        <h2 class="basic-title">Стоимость</h2>
        <h3 class="basic-subtitle">
          вашего обьекта вы можете предварительно просчитать прямо здесь
        </h3>
        <div class="calc-body">
          <div class="calc-body-parameters">
            <div class="calc-body-parameters__label">Желаемый метраж</div>
            <input class="calc-body-parameters__universal-parameter" type="text" placeholder="100-300" />
            <div class="calc-body-parameters__selects-block">
              <div class="calc-body-parameters__label">Этажность</div>
              <div class="calc-body-parameters__universal-parameter-wrap">
                <select class="calc-body-parameters__universal-parameter">
                  <option value="1">1 этаж</option>
                  <option value="2">2 этажа</option>
                  <option value="3">3 этажа</option>
                </select>
              </div>
              <div class="calc-body-parameters__label">Сроки</div>
              <div class="calc-body-parameters__universal-parameter-wrap">
                <select class="calc-body-parameters__universal-parameter">
                  <option value="8">8 месяцев</option>
                  <option value="10">10 месяцев</option>
                  <option value="12">12 месяцев</option>
                </select>
              </div>
              <div class="calc-body-parameters__label">Финансирование</div>
              <div class="calc-body-parameters__universal-parameter-wrap">
                <select class="calc-body-parameters__universal-parameter">
                  <option value="50">50%</option>
                  <option value="75">75%</option>
                  <option value="100">100%</option>
                </select>
              </div>
            </div>
          </div>
          <div class="calc-body-result">
            <div class="calc-body-result__title">
              Предварительная стоимость
            </div>
            <div class="calc-body-result__money">5 000 000 р.</div>
          </div>
          <button class="call-btn calc-btn">Рассчитать</button>
        </div>
      </div>
    </div>
  </section> -->
  <section class="basic-container">
    <div class="request__container">
      <div class="request">
        <?php if ($value = get_field('uni_form_img', 15)) { ?>
          <div class="request__bg-wrap manager-img">
            <img class="request__bg" src=<?= $value; ?> alt="" />
          </div>
        <?php } ?>
        <div class="request__content">
          <?php if ($value = get_field('uni_form_title', 15)) { ?>
            <h2 class="basic-title basic-title__consulting"><?= $value; ?></h2>
          <?php } ?>
          <?php if ($value = get_field('uni_form_subtitle', 15)) { ?>
            <h3 class="basic-subtitle"><?= $value; ?></h3>
          <?php } ?>
          <?php echo do_shortcode('[contact-form-7 id="803985b" title="Основная форма"]'); ?>
        </div>
      </div>
    </div>
  </section>
  <section class="modal-section">
    <div id="myModal" class="modal">
      <div class="modal__content">
        <div class="modal__close"></div>
        <div class="modal__title">Спасибо</div>
        <p class="modal__text">
          В ближайшее время наш консультант свяжется с Вами, чтобы ответить
          на все вопросы!
        </p>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>