<template>
  <transition
      enter-active-class="enter-active"
      leave-active-class="leave-active"
      tag="div"
      @enter="enter"
      @leave="leave"
      @before-enter="beforeEnter"
      @after-enter="afterEnter"
      @before-leave="beforeLeave"
      @after-leave="afterLeave">
    <slot/>
  </transition>
</template>

<script lang="ts">
import Vue from 'vue';
import {Component} from "vue-property-decorator";

@Component({
  name: 'transition-expand',
  components: {},
})
export default class TransitionExpand extends Vue {

  /**
   * @param {HTMLElement} element
   */
  beforeEnter(element) {
    requestAnimationFrame(() => {
      if (!element.style.height) {
        element.style.height = '0px';
      }

      element.style.display = null;
    });
  }

  /**
   * @param {HTMLElement} element
   */
  enter(element) {
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        element.style.height = `${element.scrollHeight}px`;
      });
    });
  }

  /**
   * @param {HTMLElement} element
   */
  afterEnter(element) {
    element.style.height = null;
  }

  /**
   * @param {HTMLElement} element
   */
  beforeLeave(element) {
    requestAnimationFrame(() => {
      if (!element.style.height) {
        element.style.height = `${element.offsetHeight}px`;
      }
    });
  }

  /**
   * @param {HTMLElement} element
   */
  leave(element) {
    requestAnimationFrame(() => {
      requestAnimationFrame(() => {
        element.style.height = '0px';
      });
    });
  }

  /**
   * @param {HTMLElement} element
   */
  afterLeave(element) {
    element.style.height = null;
  }
}
</script>
<style lang="scss">
.enter-active,
.leave-active {
  overflow: hidden;
  //-webkit-transition: height 0.4s e;
  //-moz-transition: height 0.4s ease;
  //-o-transition: height 0.4s ease;
  transition:
      height 500ms cubic-bezier(.46,.68,.43,1);
}
</style>
