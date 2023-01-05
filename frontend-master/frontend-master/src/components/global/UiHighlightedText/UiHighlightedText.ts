import Vue, { CreateElement, VNode } from 'vue';
import './index.scss';

function getParts(search: string, text: string) {
  if (!search || !text) {
    return [['span', { class: 'ui-highlighted-text__item' }, text]];
  }

  const parts = [...text.matchAll(new RegExp(search, 'gi'))];

  if (!parts.length) {
    return [['span', { class: 'ui-highlighted-text__item' }, text]];
  }

  return parts.reduce((result: any[], part, index) => {
    const partIndex = part.index as number;
    const prevPart = parts[index - 1];
    const prevPoint = (prevPart?.index || prevPart?.index === 0) ? prevPart.index + prevPart[0].length : 0;

    if (partIndex > prevPoint) {
      result.push(['span', { class: 'ui-highlighted-text__item' }, text.slice(prevPoint, partIndex)]);
    }

    result.push(['span', { class: 'ui-highlighted-text__item ui-highlighted-text__item_active' }, part[0]]);

    if (index === parts.length - 1 && partIndex + part[0].length !== text.length) {
      result.push(['span', { class: 'ui-highlighted-text__item' }, text.slice(partIndex + part[0].length)]);
    }

    return result;
  }, []);
}

export default Vue.extend({
  name: 'UiHighlightedText',
  props: {
    tag: {
      type: String,
      default: 'span',
    },
    text: {
      type: [String, Number],
      default: '',
    },
    search: {
      type: String,
      default: '',
    },
  },
  computed: {
    parts(): Parameters<CreateElement>[] {
      return this.getParts(this.search, this.text ? String(this.text) : '');
    },
  },
  methods: { getParts },
  render(h: CreateElement): VNode {
    return h(
      this.tag,
      { class: 'ui-highlighted-text' },
      this.parts.map((item) => h(...item))
    );
  },
});
