import { Service } from '@/plugins/service/utils';
import { Mapper } from '@/utils';
import Vue, { PluginObject } from 'vue';

/** Плагин обособления бизнес-логики. */
export default (ctx: Vue): PluginObject<{ [key: string]: typeof Service }> => ({
  install(constructor, list = {}) {
    const result = Object.entries(list).reduce((result, [name, CustomService]) => {
      const service = new CustomService(ctx);

      return {
        ...result,
        [name]: service,
      };
    }, {});

    constructor.prototype.$service = result;
    Mapper.$ctx = ctx;
  },
});

export { Service };
