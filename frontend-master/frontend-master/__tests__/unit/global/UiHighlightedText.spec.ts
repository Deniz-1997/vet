import UiHighlightedText from '@/components/global/UiHighlightedText/UiHighlightedText';
import { shallowMount } from '@vue/test-utils';

const fixtures = [
  [['span', { class: 'ui-highlighted-text__item' }, 'another one']],
  [
    ['span', { class: 'ui-highlighted-text__item' }, 'phra'],
    [
      'span',
      {
        class: 'ui-highlighted-text__item ui-highlighted-text__item_active',
      },
      'seph',
    ],
    ['span', { class: 'ui-highlighted-text__item' }, 'rase'],
  ],
  [
    [
      'span',
      {
        class: 'ui-highlighted-text__item ui-highlighted-text__item_active',
      },
      'ph',
    ],
    ['span', { class: 'ui-highlighted-text__item' }, 'phrase'],
    [
      'span',
      {
        class: 'ui-highlighted-text__item ui-highlighted-text__item_active',
      },
      'ph',
    ],
    ['span', { class: 'ui-highlighted-text__item' }, 'rase'],
  ],
  '<h2 class="ui-highlighted-text"><span class="ui-highlighted-text__item ui-highlighted-text__item_active">12</span><span class="ui-highlighted-text__item">1221</span><span class="ui-highlighted-text__item ui-highlighted-text__item_active">12</span><span class="ui-highlighted-text__item ui-highlighted-text__item_active">12</span><span class="ui-highlighted-text__item ui-highlighted-text__item_active">12</span><span class="ui-highlighted-text__item">221</span></h2>',
];

const createComponent = (options = {}) =>
  shallowMount<any>(UiHighlightedText, {
    ...options,
  });

describe('UiHighlightedText.vue', () => {
  test('getParts', () => {
    const component = createComponent();
    expect(component.vm.getParts('phrase', 'another one')).toEqual(fixtures[0]);
    expect(component.vm.getParts('seph', 'phrasephrase')).toEqual(fixtures[1]);
    expect(component.vm.getParts('ph', 'phrasephrase')).toEqual(fixtures[2]);
  });

  test('renders', () => {
    const component = createComponent({
      propsData: {
        tag: 'h2',
        text: '1221121212221',
        search: '12',
      },
    });

    expect(component.html()).toBe(fixtures[3]);
  });
});
