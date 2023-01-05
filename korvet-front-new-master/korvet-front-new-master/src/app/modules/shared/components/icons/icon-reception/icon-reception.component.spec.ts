import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { IconReceptionComponent } from './icon-reception.component';

describe('IconReceptionComponent', () => {
  let component: IconReceptionComponent;
  let fixture: ComponentFixture<IconReceptionComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ IconReceptionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IconReceptionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
