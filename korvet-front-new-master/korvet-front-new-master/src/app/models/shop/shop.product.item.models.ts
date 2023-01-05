import {constructByInterface} from '../../utils/construct-by-interface';
import { CashReceiptInterface } from '../cash/cash.receipt.models';
import { ReferenceAppointmentStatusInterface } from '../reference/reference.appointment.status.models';
import { ReferenceProductInterface, ReferenceProductModel } from '../reference/reference.product.models';
import { ReferenceUnitModel } from '../reference/reference.unit.models';
import { ReferenceStockModel } from '../reference/stock';
import { UserModels } from '../user/user.models';
import { ShopOrderInterface, ShopOrderModel } from './shop.order.models';

export interface ShopProductItemInterface {
  id: number;
  product: ReferenceProductInterface;
  user: null | UserModels;
  stock: ReferenceStockModel;
  quantity: number;
  price: number;
  amount: number;
  shopOrder: ShopOrderInterface;
 }

export class ShopProductItemModel implements ShopProductItemInterface {
  id: number;
  product: ReferenceProductModel;
  user: null | UserModels;
  stock: ReferenceStockModel;
  quantity: number;
  price: number;
  amount: number;
  shopOrder: ShopOrderModel;

  constructor(o?: ShopProductItemInterface) {
    constructByInterface(o, this);
  }
}
