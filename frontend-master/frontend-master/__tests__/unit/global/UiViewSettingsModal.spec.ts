import Vue from 'vue';
import UiViewSettingsModal from '@/components/global/UiViewSettingsModal/UiViewSettingsModal.vue';
import { mount } from '@vue/test-utils';
import { injectVuetify } from '../__utils__/helpers';

describe('UiViewSettingsModal.vue', () => {
  test('render', async () => {
    const component = mount(UiViewSettingsModal, {
      ...injectVuetify(),
      propsData: {
        id: 'test',
        value: {
          actual: true,
          filter: 'test',
          pageable: {
            pageNumber: 0,
            pageSize: 10,
            sort: [
              { property: 'test1', direction: 'ASC' },
              { property: 'test2', direction: 'DESC' },
            ],
          },
          columns: [
            { value: 'test1', text: 'test1', sortAs: 'test1' },
            { value: 'test2', text: 'test11', sortAs: 'test2' },
          ],
        },
      },
      mocks: { $service: { notify: { flush: jest.fn() } } },
    });
    await component.setData({ isShow: true });
    expect(component.find('span.settingsSpan').isVisible()).toBe(true);
    expect(component.findAll('div.v-select').at(0).text()).toBe('test1test11');
    expect(component.findAll('div.select-row')).toHaveLength(2);
    expect(component.findAll('div.select-row button')).toHaveLength(1);
    expect(component.findAll('.row.justify-end button span').at(0).text()).toBe('Сбросить');
    expect(component.findAll('.row.justify-end button span').at(2).text()).toBe('Отмена');
    expect(component.findAll('.row.justify-end button span').at(4).text()).toBe('Применить');
  });

  test('apply settings', async () => {
    const component = mount(UiViewSettingsModal, {
      ...injectVuetify(),
      propsData: {
        id: 'test',
        value: {
          actual: true,
          filter: 'test',
          pageable: {
            pageNumber: 0,
            pageSize: 10,
            sort: [
              { property: 'test1', direction: 'ASC' },
              { property: 'test2', direction: 'DESC' },
            ],
          },
          columns: [
            { value: 'test1', text: 'test1', sortAs: 'test1' },
            { value: 'test2', text: 'test11', sortAs: 'test2' },
          ],
        },
      },
      mocks: { $service: { notify: { flush: jest.fn() } } },
    });
    await component.setData({ isShow: true });
    await component.findAll('.row.justify-end button span').at(4).trigger('click');
    expect(component.emitted()['apply-settings']).toHaveLength(2);
  });

  test('reset settings', async () => {
    const component = mount(UiViewSettingsModal, {
      ...injectVuetify(),
      propsData: {
        id: 'test',
        value: {
          actual: true,
          filter: 'test',
          pageable: {
            pageNumber: 0,
            pageSize: 10,
            sort: [
              { property: 'test1', direction: 'ASC' },
              { property: 'test2', direction: 'DESC' },
            ],
          },
          columns: [
            { value: 'test1', text: 'test1', sortAs: 'test1' },
            { value: 'test2', text: 'test11', sortAs: 'test2' },
          ],
        },
      },
      mocks: { $service: { notify: { flush: jest.fn() } } },
    });
    await component.setData({ isShow: true, form: {} });
    await component.findAll('.row.justify-end button span').at(2).trigger('click');
    expect((component.vm as any).form).toMatchObject({
      pageable: {
        sort: [
          { property: 'test1', direction: 'ASC' },
          { property: 'test2', direction: 'DESC' },
        ],
      },
      columns: [
        { value: 'test1', text: 'test1', sortAs: 'test1' },
        { value: 'test2', text: 'test11', sortAs: 'test2' },
      ],
    });
  });
});
