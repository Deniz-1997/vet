import { AnnualValue } from './AnnualValue';
import { Property } from './Property';

export interface PropertyBudgetPeriodValues {
  property: Property;
  annualPropertyValues: AnnualValue[];
}
