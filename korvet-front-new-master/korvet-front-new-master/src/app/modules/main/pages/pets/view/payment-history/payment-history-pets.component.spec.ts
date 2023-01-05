import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {PaymentHistoryPetsComponent} from './payment-history-pets.component';

describe('OwnersViewDocumentsComponent', () => {
  let component: PaymentHistoryPetsComponent;
  let fixture: ComponentFixture<PaymentHistoryPetsComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [PaymentHistoryPetsComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PaymentHistoryPetsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
