import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { ModalFileAddFormComponent } from './modal-file-add-form.component';

describe('ModalFileAddFormComponent', () => {
  let component: ModalFileAddFormComponent;
  let fixture: ComponentFixture<ModalFileAddFormComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ ModalFileAddFormComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ModalFileAddFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
