import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';
import { select, Store } from '@ngrx/store';
import { Observable } from 'rxjs';
import { debounceTime, distinctUntilChanged, takeUntil } from 'rxjs/operators';
import {CrudState} from 'src/app/api/api-connector/crud/crud-store.service';
import {LoadGetListAction} from 'src/app/api/api-connector/crud/crud.actions';
import {getCrudModelGetListLoading} from 'src/app/api/api-connector/crud/crud.selectors';
import { CrudType } from 'src/app/common/crud-types';
import { ReferenceCategoryNomenclatureModel } from 'src/app/models/reference/reference.category.nomenclature.models';
import { ReferenceProductModel } from 'src/app/models/reference/reference.product.models';

@Component({
  selector: 'app-shop-catalog',
  templateUrl: './shop-catalog.component.html',
  styleUrls: ['./shop-catalog.component.css']
})
export class ShopCatalogComponent implements OnInit {
  @Output() selectedProduct: EventEmitter<any> = new EventEmitter();
  _stock: number;
  get stock(): number {
    return this._stock;
  }
  @Input() set stock(value: number) {
    this._stock = value;
    this.loadProducts();
    this.getCategoriesByParent(null);
  }
  loading$: Observable<boolean>;
  categotiesList: Array<ReferenceCategoryNomenclatureModel> = [];
  allCategories: Array<ReferenceCategoryNomenclatureModel> = [];
  selectedCategory: ReferenceCategoryNomenclatureModel;
  productsList: Array<ReferenceProductModel> = [];
  type = CrudType.ReferenceCategoryNomenclature;
  productsLoading: boolean;
  formGroup: FormGroup = new FormGroup({
    seachInput: new FormControl(''),
    showAllProducts: new FormControl(false)
  });

  constructor(protected store: Store<CrudState>) {
    this.loading$ = this.store.pipe(select(getCrudModelGetListLoading, { type: this.type }));
    this.formGroup.controls.seachInput.valueChanges.pipe(
      debounceTime(1000),
      distinctUntilChanged()
    )
      .subscribe(_ => {
        if (this.formGroup.controls.seachInput.value && this.formGroup.controls.seachInput.value.length) {
          this.loadProducts();
        }
      });
  }

  ngOnInit(): void {
    this.loadCategories();
  }

  loadCategories(offset: number = 0) {
    const limit: number = 100;
    this.store.dispatch(new LoadGetListAction({
      type: this.type,
      params: {
        limit: limit,
        offset: limit * offset,
      },
      onSuccess: (res) => {
        if (res.status === true && res.response) {
          this.allCategories = this.allCategories.concat(res.response.items);
          console.log(res.response.totalCount);
          if (res.response.totalCount > (offset + 1) * limit) {
            this.loadCategories(++offset);
          }
        }
      }
    }));
  }

  getCategoriesByParent(category: ReferenceCategoryNomenclatureModel) {
    this.selectedCategory = category;
    this.formGroup.controls.seachInput.setValue(null);
    this.loadProducts();
    this.categotiesList = category == null ? this.allCategories.filter(n => n.parent == null) : this.allCategories.filter(n => n.parent && n.parent.id === category.id);
  }

  getSelectedCategoryParents() {
    if (!this.selectedCategory) return null;
    let parent: ReferenceCategoryNomenclatureModel = this.selectedCategory.parent;
    let parentsList: Array<ReferenceCategoryNomenclatureModel> = [];
    parentsList.push(this.selectedCategory);
    while (parent) {
      parentsList.push(parent);
      parent = this.allCategories.find(n => parent.parent && n.id === parent.parent.id);
    }
    parentsList.reverse();
    return parentsList;
  }

  loadProducts() {
    if (!this.stock) return;
    this.productsLoading = true;
    let filter = {};
    filter['paymentObject'] = 'COMMODITY';
    if (this.formGroup.controls.seachInput.value) {
      filter['~name'] = this.formGroup.controls.seachInput.value;
      this.selectedCategory = null;
    }
    else {
      filter['categoryNomenclature'] = this.selectedCategory ? { id: this.selectedCategory.id } : null;

    }
    filter['productStock'] = { stock: { id: this.stock } };
    this.store.dispatch(new LoadGetListAction({
      type: CrudType.ReferenceProduct,
      params: {
        order: { surname: 'ASC' },
        offset: 0,
        limit: 50,
        filter: filter,
      },
      onSuccess: (res) => {
        this.productsLoading = false;
        if (res.status === true) {
          this.productsList = res.response.items;
        }
      },
      onError: _ => {
        this.productsLoading = false;
      }
    }));
  }

  addProductToCart(product) {
    this.selectedProduct.emit(product);
  }

  getProductStockCount(product: ReferenceProductModel) {
    let productStock = product.productStock.find(n => n.stock.id == this._stock);
    return productStock ? productStock.quantity : 0;
  }

  selectedIndexChange(tabNumber) {
    this.getCategoriesByParent(null);
  }
}
