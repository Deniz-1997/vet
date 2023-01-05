import { Component, Vue, Model, Watch } from 'vue-property-decorator';

@Component
export default class Modal extends Vue {
  @Model('toggle', { type: Boolean }) readonly $isModalOpen!: boolean;

  protected get isModalOpen() {
    return this.$isModalOpen;
  }

  protected set isModalOpen(v) {
    this.$emit('toggle', v);
  }

  protected onModalOpen() {
    // do nothing.
  }

  protected onModalClose() {
    // do nothing.
  }

  @Watch('isModalOpen')
  private onModalToggle(value: boolean) {
    const handler = value ? this.onModalOpen : this.onModalClose;
    handler();

    if (!value) {
      this.$emit('close');
    }
  }
}
