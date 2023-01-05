import store from '@/store';
import { QualityIndicatorsVueModel } from '@/models/Lot/QualityIndicators.vue';
import { IndicatorPurposeEnum } from '@/utils/enums/indicatorPurpose.enum';

const defaultParams = {
  actual: true,
  only_with_limits: true,
  limits_criteria: {
    actual: true,
    country_alpha2: 'RU',
  },
};

/**
 * @param {Object} params
 * @return QualityIndicatorsVueModel[]
 */
export async function loadQualityIndicatorsByParams(params: {
  okpd2: string;
  purposes: IndicatorPurposeEnum[];
  country_alpha2?: string;
}) {
  const { content } = await store.dispatch('nsi/getList', {
    url: '/api/nci/qualityIndicators/msh',
    params: {
      ...defaultParams,
      limits_criteria: {
        ...defaultParams.limits_criteria,
        okpd2: params.okpd2,
        purposes: params.purposes,
        country_alpha2: params.country_alpha2 || 'RU',
      },
    },
  });

  if (content.filter((e) => e.limits?.length > 1).length) {
    throw new Error();
  }

  return content
    .map(mapArray)
    .filter((v) => v !== null)
    .sort((a: any, b: any) => a.name.localeCompare(b.name));
}

function renderSymbol(symbol) {
  return symbol ? `(${symbol})` : '';
}

function mapArray(item) {
  const limit = item.limits[0];
  const valueFrom = limit.min_value || 0;
  const valueTo = limit.max_value || 0;

  const limits = {
    valueFrom: valueFrom,
    valueTo: valueTo,
  };

  return {
    quality_indicator_id: item.id,
    value: undefined,
    name: `${item.name} ${renderSymbol(item.unitOfMeasure?.symbol)} `,
    measure: item.unitOfMeasure?.symbol ?? '',
    type: limit.type,
    values: limit.values,
    ...limits,
  };
}

/**
 * Возвращает массив потребсвойств, с перенесенными значениями свойств
 * @param target цель изменения
 * @param source источник
 * @param properties свойства
 */
export function mergeQualityIndicators(
  target: QualityIndicatorsVueModel[],
  source: QualityIndicatorsVueModel[],
  properties: string[]
) {
  return target.map((value: QualityIndicatorsVueModel) => {
    source.map((n2: QualityIndicatorsVueModel) => {
      if (value.quality_indicator_id === n2.quality_indicator_id) {
        properties.forEach((prop) => {
          value[prop] = n2[prop];
        });
      }
    });

    return value;
  });
}
