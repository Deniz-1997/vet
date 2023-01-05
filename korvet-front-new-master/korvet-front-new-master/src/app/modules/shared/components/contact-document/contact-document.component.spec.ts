import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { ContactDocumentComponent } from './contact-document.component';

describe('ContactDocumentComponent', () => {
  let component: ContactDocumentComponent;
  let fixture: ComponentFixture<ContactDocumentComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ ContactDocumentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ContactDocumentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
