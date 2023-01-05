import {constructByInterface} from '../../utils/construct-by-interface';
import { AppointmentStateModel } from '../appointment/appointment-state.models';
import { CashReceiptInterface, CashReceiptModel } from '../cash/cash.receipt.models';
import { ProductStockModel } from '../product/product-stock.models';
import { ReferenceAppointmentStatusInterface, ReferenceAppointmentStatusModel } from '../reference/reference.appointment.status.models';
import { ReferenceUnitInterface, ReferenceUnitModel } from '../reference/reference.unit.models';
import { UserModels } from '../user/user.models';
import { ShopProductItemInterface, ShopProductItemModel } from './shop.product.item.models';

export interface ShopOrderInterface {
  id: number;
  deleted: boolean;
  date: string;
  user: { id: number, name: string };
  unit: ReferenceUnitInterface;
  cashReceipt: CashReceiptInterface | null;
  documentProducts: ShopProductItemInterface[];
  paymentState: { code: string; title: string };
  paymentType: { code: string; title: string };
  stock: { id: number, name: string };
  state: {
    code: string,
    title: string
  };
 }

export class ShopOrderModel implements ShopOrderInterface {
  id: number;
  deleted: boolean;
  date: string;
  user: { id: number, name: string, surname: string, patronymic: string };
  unit: ReferenceUnitModel;
  cashReceipt: CashReceiptModel | null;
  documentProducts: ShopProductItemModel[];
  paymentState: { code: string; title: string };
  paymentType: { code: string; title: string };
  stock: { id: number, name: string };
  state: {
    code: string,
    title: string
  };

  constructor(o?: ShopOrderInterface) {
    constructByInterface(o, this);
  }
}
