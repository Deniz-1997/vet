<template>
  <div class="footer">
    <v-container>
      <v-row>
        <v-col cols="9" class="containerAddress">
          <div class="address">
            <img src="/icons/union.svg" class="union" />
            <span class="spanAddress">107996, Москва, Орликов переулок, 1/11</span>
          </div>
        </v-col>
        <v-col cols="3">
          <div class="containerInstructions">
            <a href="https://specagro.ru/hotline" target="_blank" class="spanSupport">Техподдержка</a>
          </div>
        </v-col>
        <hr size="1" class="line" />
        <v-col cols="12">
          <div class="copyright">
            <a href="https://mcx.gov.ru/" target="_blank" class="spanCopyright">
              © Министерство сельского хозяйства РФ, 2022
            </a>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  name: 'footer-component',
})
export default class FooterComponent extends Vue {}
</script>

<style lang="scss" scoped>
@import '@/assets/styles/_variables.scss';
@import '@/assets/styles/_mixins.scss';

.footer {
  background-repeat: no-repeat;
  background-color: $footer-color;
  background-size: 100%;
  width: 100%;
  min-height: 128px;
}

.address {
  flex-direction: row;
  align-items: center;
  display: flex;
  margin-bottom: 0;

  @include respond-to('small') {
    margin-bottom: 10px;
  }
}

.spanAddress {
  color: $white-color;
  font-weight: 500;
  font-size: 16px;
  line-height: 48px;

  @include respond-to('medium') {
    font-size: 15px;
  }

  @include respond-to('small') {
    font-size: 12px;
    line-height: 22px;
  }
}

.spanCopyright {
  color: $medium-grey-color;
  font-weight: 500;
  font-size: 15px;
  line-height: 32px;
  transition: all 0.3s ease;

  &:hover {
    color: $white-color;
    text-decoration: underline;
  }

  @include respond-to('medium') {
    font-size: 14px;
  }

  @include respond-to('small') {
    font-size: 10px;
    line-height: 0;
  }
}

.spanSupport {
  color: $white-color;
  font-weight: 500;
  font-size: 15px;
  line-height: 32px;
  transition: all 0.3s ease;

  @include respond-to('medium') {
    font-size: 14px;
  }

  @include respond-to('small') {
    font-size: 10px;
    line-height: 0;
  }
}

.union {
  margin-right: 16px;
  @include respond-to('small') {
    width: 20px;
    height: 20px;
  }
}

.line {
  width: 100%;
  z-index: 0;
  opacity: 0.2;
}

.containerInstructions {
  color: $white-color;
  cursor: pointer;
  font-size: 16px;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  height: 100%;

  &:hover {
    text-decoration: underline;
  }

  @include respond-to('medium') {
    font-size: 15px;
  }

  @include respond-to('small') {
    font-size: 12px;
  }
}
</style>
