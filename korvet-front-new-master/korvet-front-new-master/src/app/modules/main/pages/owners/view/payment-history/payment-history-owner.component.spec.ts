import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {PaymentHistoryOwnerComponent} from './payment-history-owner.component';

describe('OwnersViewDocumentsComponent', () => {
  let component: PaymentHistoryOwnerComponent;
  let fixture: ComponentFixture<PaymentHistoryOwnerComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [PaymentHistoryOwnerComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PaymentHistoryOwnerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
