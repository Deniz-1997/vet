import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ShopSettingsEditComponent } from './shop-settings-edit.component';

describe('ShopSettingsEditComponent', () => {
  let component: ShopSettingsEditComponent;
  let fixture: ComponentFixture<ShopSettingsEditComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ ShopSettingsEditComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(ShopSettingsEditComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
