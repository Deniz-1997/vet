import { Component, Vue, Model, Watch } from 'vue-property-decorator';
import cloneDeep from 'lodash/cloneDeep';
import isEqual from 'lodash/isEqual';
import { Debounce } from '@/utils/global/decorators/method';

@Component
export default class Form extends Vue {
  @Model('change', { type: [Object, Array], default: () => ({}) }) protected readonly value!: any;

  public form: any = {};
  public ready = false;
  firstChange = false;

  created() {
    this.form = cloneDeep(this.value);
    this.ready = true;
  }

  @Watch('value', { deep: true, immediate: true })
  @Debounce(100)
  private $onValueChange(value) {
    if (!isEqual(value, this.form)) {
      this.form = cloneDeep(value);
      this.ready = true;
    }
  }

  @Watch('form', { deep: true })
  private $onFormChange(form) {
    if (!isEqual(form, this.value)) {
      this.$emit('change', cloneDeep(form));

      if (!this.firstChange) {
        this.firstChange = true;
        this.$emit('first-change');
      }
    }
  }
}
