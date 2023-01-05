export enum LotType {
  /** Создание по результатам гос. мониторинга. */
  FIELD = 'field',
  /** Создана из зерна других партий. */
  ANOTHER_BATCH_GRAIN = 'another-batch-grain',
  /** Создание партии зерна из остатков. */
  RESIDUES = 'residues',
  /** Создана из зерна на основе бумажного СДИЗ. */
  SDIZ = 'sdiz',
  /** Создана из ввозимого зерна. */
  IMPORTED = 'imported',
  /** Создана при погашении СДИЗ. */
  EXTINGUISH = 'extinguish',
  /** Создана при возврате (аннулирование погашения) части партии из СДИЗ. */
  PART = 'part',
  /** Создана продуктов переработки зерна при производстве. */
  IN_PRODUCT = 'in-product',
}