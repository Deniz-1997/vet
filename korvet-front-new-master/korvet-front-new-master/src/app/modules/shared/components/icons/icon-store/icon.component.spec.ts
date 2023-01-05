import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { IconStoreComponent } from './icon.component';

describe('IconStoreComponent', () => {
  let component: IconStoreComponent;
  let fixture: ComponentFixture<IconStoreComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ IconStoreComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IconStoreComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
