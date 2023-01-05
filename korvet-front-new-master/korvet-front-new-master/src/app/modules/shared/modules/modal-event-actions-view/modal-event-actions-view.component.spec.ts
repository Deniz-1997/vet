import {ComponentFixture, TestBed, waitForAsync} from '@angular/core/testing';

import {ModalEventActionsViewComponent} from './modal-event-actions-view.component';

describe('ModalEventActionsViewComponent', () => {
  let component: ModalEventActionsViewComponent;
  let fixture: ComponentFixture<ModalEventActionsViewComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ModalEventActionsViewComponent]
    })
      .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalEventActionsViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
