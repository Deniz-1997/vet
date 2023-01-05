import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShopCatalogComponent } from './shop-catalog.component';

describe('ShopCatalogComponent', () => {
  let component: ShopCatalogComponent;
  let fixture: ComponentFixture<ShopCatalogComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShopCatalogComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ShopCatalogComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
