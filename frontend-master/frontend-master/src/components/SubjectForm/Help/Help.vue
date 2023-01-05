<template>
  <div class="help-block">
    <div class="help-block__title">
      <v-icon class="icon">mdi-information-outline</v-icon>
      <span>Информация по заполнению</span>
    </div>

    <div class="help-block__description">
      <p>Обязательные для заполнения поля отмечены символом звездочка (<span class="color">*</span>).</p>
      <p>Для перехода к <span class="bold">следующему разделу</span>, необходимо:</p>
      <ul class="help-block__list">
        <li>
          <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M19.8293 0.123719C19.6214 -0.0581225 19.3056 -0.0370638 19.1237 0.170755C18.9419 0.378573 18.9629 0.694454 19.1707 0.876295L22.1693 3.50001H0V4.50001H22.1693L19.1707 7.12372C18.9629 7.30556 18.9419 7.62144 19.1237 7.82926C19.3056 8.03708 19.6214 8.05814 19.8293 7.8763L23.8293 4.3763C23.9378 4.28135 24 4.14419 24 4.00001C24 3.85583 23.9378 3.71866 23.8293 3.62372L19.8293 0.123719Z"
              fill="#828286"
            />
          </svg>

          <span>заполнить все обязательные поля;</span>
        </li>
        <li>
          <svg width="24" height="8" viewBox="0 0 24 8" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              clip-rule="evenodd"
              d="M19.8293 0.123719C19.6214 -0.0581225 19.3056 -0.0370638 19.1237 0.170755C18.9419 0.378573 18.9629 0.694454 19.1707 0.876295L22.1693 3.50001H0V4.50001H22.1693L19.1707 7.12372C18.9629 7.30556 18.9419 7.62144 19.1237 7.82926C19.3056 8.03708 19.6214 8.05814 19.8293 7.8763L23.8293 4.3763C23.9378 4.28135 24 4.14419 24 4.00001C24 3.85583 23.9378 3.71866 23.8293 3.62372L19.8293 0.123719Z"
              fill="#828286"
            />
          </svg>

          <span>нажать на кнопку «<span class="color">Далее</span>» в верхней правой части экрана.</span>
        </li>
      </ul>

      <p>
        Для перехода к <span class="bold">предыдущему шагу</span> нажмите кнопку «<span class="color">Назад</span>» в
        верхней правой части экрана.
      </p>

      <p>
        Для перехода к <span class="bold">любому из пройденных шагов</span> воспользуйтесь вкладками в верхней левой
        части экрана.
      </p>

      <p>
        Для <span class="bold">отмены всех действий</span> и возврата к реестру нажмите кнопку «<span class="color"
          >Отмена</span
        >» в верхней правой части экрана.
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  name: 'help-component',
})
export default class HelpComponent extends Vue {}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables';
@import '@/assets/styles/_mixins';
.help-block {
  padding: 15px 20px 55px;
  background: rgba($silver-color, 0.3);
  border-radius: 4px;

  &__title {
    align-items: center;
    display: flex;
    font-size: 14px;
    line-height: 16px;
    font-weight: 700;
    height: 38px;
    margin-bottom: 8px;

    .icon {
      margin-right: 8px;
    }
  }

  &__description {
    color: $medium-grey-color;
    font-size: 14px;
    line-height: 20px;
  }

  &__list {
    margin-bottom: 8px;
  }

  p {
    margin-bottom: 8px;
  }

  svg {
    display: inline-block;
    margin-right: 10px;
  }

  .bold {
    font-weight: 700;
  }

  .color {
    color: $gold-dark-color;
  }
}
</style>
