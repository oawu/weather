
(function($) {

  $.widget ( "custom.OA_LockScan", {
    options: {
      isBlur: false,
      opacity: '0.7',
      color: 'rgba(154, 154, 154, 1)',
      isLockscroll: true,
      isEffectAnimate: false,
      effect: 'OA-ui-lockScan-effect'
    },
    _create: function() {
      var that = this;
      that.obj = that.element;
      that.overflow = $('body').css('overflow');
    },
    _init: function () {
      var that = this;
      that.obj
            .empty ()
            .addClass ('OA-ui-lockScan')
            .addClass (that.options.effect)
            .addClass (that.options.isEffectAnimate?'animate':'')
            .css ({
              'position': 'fixed',
              'top': '0px',
              'left': '0px',
              'width': '100%',
              'height': '100%',
              'opacity': that.options.opacity,
              'background-color': that.options.color
            })
            .hide ();
      if (that.options.isBlur) that.obj.addClass ('OA_HoverDir-blur')
    },
    open: function () {
      var that = this;
      if (!that.obj.is(':visible')) {
        if (that.options.isLockscroll) {
          $('body').css('overflow', 'hidden');
        }
        that.obj.show ();
      }
      return that.obj;
    },
    close: function () {
      var that = this;
      if (that.obj.is(':visible')) {
        if (that.options.isLockscroll) {
          $('body').css('overflow', that.overflow);
        }
        that.obj.hide ();
      }
      return that.obj;
    }
  });

  $.widget ( "custom.OA_Bubble", {
    options: {
      inSpeed: 'fast',
      outSpeed: 'fast',
      inEasing: 'swing',
      outEasing: 'swing',
      inOpacity: '1',
      outOpacity: '0.6',
      scaleType: 'center',
      disabled: false,
      size: {
        width: '32px',
        height: '32px'
      },
      scale: {
        width: '70px',
        height: '70px'
      }
    },
    _create: function() {
      var that = this;
      that.obj = that.element;

      that.outInfo = {
        top: that.obj.css ('top'),
        left: that.obj.css ('left'),
        width: (parseFloat (that.obj.width())>0?parseFloat (that.obj.width()):parseFloat (that.options.size.width)) + 'px',
        height: (parseFloat (that.obj.height())>0?parseFloat (that.obj.height()):parseFloat (that.options.size.height)) + 'px'
      };

      t = (parseFloat (that.outInfo.top) + ((0 - parseFloat (that.options.scale.height) + parseFloat (that.outInfo.height)) / 2)) + 'px';
      l = (parseFloat (that.outInfo.left) + ((0 - parseFloat (that.options.scale.width) + parseFloat (that.outInfo.width)) / 2)) + 'px';
      switch (that.options.scaleType) {
        default: case 'center':
          break;
        case 'left top': case 'top left':
          l = '0px';
        case 'top': case 'top center': case 'center top':
          t = '0px';
          break;

        case 'left bottom': case 'bottom left':
          t = (parseFloat (that.outInfo.top) + (0 - parseFloat (that.options.scale.height) + parseFloat (that.outInfo.height))) + 'px';
        case 'left': case 'left center': case 'center left':
          l = '0px';
          break;

        case 'right top': case 'top right':
          t = '0px';
        case 'right': case 'right center': case 'center right':
          l = (parseFloat (that.outInfo.left) + (0 - parseFloat (that.options.scale.width) + parseFloat (that.outInfo.width))) + 'px';
          break;

        case 'right bottom': case 'bottom right':
          l = (parseFloat (that.outInfo.left) + (0 - parseFloat (that.options.scale.width) + parseFloat (that.outInfo.width))) + 'px';
        case 'bottom': case 'bottom center': case 'center bottom':
          t = (parseFloat (that.outInfo.top) + (0 - parseFloat (that.options.scale.height) + parseFloat (that.outInfo.height))) + 'px';
          break;
      }

      that.inInfo = {
        top: t,
        left: l,
        width: parseFloat (that.options.scale.width) + 'px',
        height: parseFloat (that.options.scale.height) + 'px'
      };
    },
    _init: function () {
      var that = this;

      that.obj
            .css ({
              'opacity': that.options.outOpacity,
              'position': 'absolute',
              'width': parseFloat (that.outInfo.width) + 'px',
              'height': parseFloat (that.outInfo.height) + 'px'
            })
            .hover (function () {
              that.Larger ();
            }, function () {
              that.Smaller ();
            });
    },
    setDisable: function (isDisable) {
      that.options.disabled = isDisable;
    },
    Larger: function () {
      var that = this;
      if (!that.options.disabled) {
        that.obj
          .stop ()
          .css ({
            'opacity': that.options.inOpacity,
            'top': parseFloat (that.outInfo.top) + 'px',
            'left': parseFloat (that.outInfo.left) + 'px',
            'width': parseFloat (that.outInfo.width) + 'px',
            'height': parseFloat (that.outInfo.height) + 'px',
            'z-index': 100000
          })
          .animate ({
            'top': parseFloat (that.inInfo.top) + 'px',
            'left': parseFloat (that.inInfo.left) + 'px',
            'width': parseFloat (that.inInfo.width) + 'px',
            'height': parseFloat (that.inInfo.height) + 'px'
            },
            that.options.inSpeed,
            that.options.inEasing
          );
      }
      return that.obj;
    },
    Smaller: function () {
      var that = this;
      that.obj
        .stop ()
        .css ({
          'opacity': that.options.outOpacity,
          'top': parseFloat (that.inInfo.top) + 'px',
          'left': parseFloat (that.inInfo.left) + 'px',
          'width': parseFloat (that.inInfo.width) + 'px',
          'height': parseFloat (that.inInfo.height) + 'px',
          'z-index': 99999
        })
        .animate ({
          'top': parseFloat (that.outInfo.top) + 'px',
          'left': parseFloat (that.outInfo.left) + 'px',
          'width': parseFloat (that.outInfo.width) + 'px',
          'height': parseFloat (that.outInfo.height) + 'px'
          },
          that.options.outSpeed,
          that.options.inEasing
        );
      return that.obj;
    }
  });

  $.widget ( "custom.OA_Dialog", {
    options: {
      title: '',
      content: '',
      buttons: {},
      isLockScan: true,
      openEffect: null,
      closeEffect: null,
      isAutoOpen: false,
      topHeight: 'auto',
      bottomHeight: 'auto',
      contentVerticalPadding: '5px',
      minSize: {
        width: '250px',
        height: '150px'
      },
      maxSize: {
        width: '350px',
        height: '350px'
      },
      openPosition: {
        top: '200px',
        left: '-1px'
      }
    },
    _create: function() {
      var that = this;
      that.obj = that.element;

      that.title = (that.options.title==''?(typeof that.obj.attr ('title')==='undefined'?'':that.obj.attr ('title')):that.options.title);
      that.content = (that.options.content==''?(typeof that.obj.html ()==='undefined'?'':(typeof that.content==='undefined'?(that.obj.html ()):that.content)):that.options.content);
     
    },
    _init: function () {
      var that = this;
      that.obj
            .empty ()
            .removeAttr('title')
            .addClass ('OA-ui-OA_Dialog')
            .css ({
              'position': 'absolute',
              'min-width': parseFloat (that.options.minSize.width) + 'px',
              'min-height': parseFloat (that.options.minSize.height) + 'px',
              'max-width': parseFloat (that.options.maxSize.width) + 'px',
              'max-height': parseFloat (that.options.maxSize.height) + 'px'
            });

            
            var topDiv = $('<div />')
                                .empty ()
                                .text (that.title)
                                .addClass ('OA-ui-OA_Dialog-topDiv')
                                .css ({
                                  'height': parseFloat (that.options.topHeight) + 'px',
                                  'max-height': parseFloat (that.options.topHeight) + 'px',
                                  'line-height': parseFloat (that.options.topHeight) + 'px'
                                })
                                .appendTo (that.obj);

            that.obj.draggable ({
              handle: topDiv,
              scroll: false
            })

            var maxHeight = (parseFloat (that.options.maxSize.height) - parseFloat (that.options.topHeight) - 2  - (that.options.buttons.length==0?0:(parseFloat (that.options.bottomHeight ) + 2)) - parseFloat (that.obj.css ('border-right-width')) - parseFloat (that.obj.css ('border-left-width')) - (parseFloat (that.options.contentVerticalPadding) * 2) - parseFloat (that.obj.css ('padding-top')) - parseFloat (that.obj.css ('padding-bottom')));
            var contentDiv = $('<div />')
                                .empty ()
                                .html (that.content)
                                .addClass ('OA-ui-OA_Dialog-contentDiv')
                                .css ({
                                  // 'padding-top': parseFloat (that.options.contentVerticalPadding) + 'px',
                                  // 'padding-bottom': parseFloat (that.options.contentVerticalPadding) + 'px',
                                  'min-height': (parseFloat (that.options.minSize.height) - parseFloat (that.options.topHeight) - 2  - (that.options.buttons.length==0?0:(parseFloat (that.options.bottomHeight ) + 2)) - parseFloat (that.obj.css ('border-right-width')) - parseFloat (that.obj.css ('border-left-width')) - (parseFloat (that.options.contentVerticalPadding) * 2)) + 'px',
                                  'max-height': maxHeight<0?0:maxHeight + 'px'
                                })
                                .appendTo (that.obj);

            if (Object.keys(that.options.buttons).length) {

              var bottomDiv = $('<div />')
                      .empty ()
                      .addClass ('OA-ui-OA_Dialog-bottomDiv')
                      .css ({
                        'height': parseFloat (that.options.bottomHeight) + 'px'
                      })
                      .appendTo (that.obj);

              var div = $('<div />').empty ().appendTo (bottomDiv)

              $.each (that.options.buttons, function (name, value) {
                
                $('<input />')
                  .attr ('type', 'button')
                  .empty ()
                  .val (name)
                  .addClass ('OA-ui-OA_Button')
                  .appendTo (div)
                  .click ($.proxy (value, that.obj))
                  ;
              });
            }
      if (!that.lockScanObj)
        that.lockScanObj = $('<div />')
                             .empty ().css ({'z-index': 9998})
                             .OA_LockScan ()
                             .click (function () {
                               if (!that.options.isLockScan) {
                                 that.close ();
                               }
                             })
                             .insertBefore(that.obj);

      that._resetPosition ();
      that.obj.css ({'z-index': 9999}).hide ();
      if (that.options.isAutoOpen) {
        that.open ();
      }
    },
    _resetPosition: function () {
      var that = this;

      that.openPosition = {
        'top': (parseFloat (parseFloat (that.options.openPosition.top)<0?((parseFloat ($(window).height()) - parseFloat (that.obj.height())) / 2):(that.options.openPosition.top)) + parseFloat ($(window).scrollTop())) + 'px',
        'left': (parseFloat (parseFloat (that.options.openPosition.left)<0?((parseFloat ($(window).width()) - parseFloat (that.obj.width())) / 2):(that.options.openPosition.left)) + parseFloat ($(window).scrollLeft())) + 'px'
      }

      that.obj.css ({
              'top': parseFloat (that.openPosition.top) + 'px',
              'left': parseFloat (that.openPosition.left) + 'px'
            });
    },
    open: function () {
      var that = this;

      that._resetPosition ();
      that.lockScanObj.OA_LockScan ('open');
      that.obj.show (that.options.openEffect);

      return that.obj;
    },
    close: function () {
      var that = this;

      that.lockScanObj.OA_LockScan ('close');
      that.obj.hide (that.options.closeEffect);
      return that.obj;
    },
    option: function (key, value) {
      var that = this;

      that.options[key] = value;

      that._create ();
      that._init ();
      return that.obj;
    }
  });

  $.widget ( "custom.OA_BallMenu", {
    options: {
      padding: '5px',
      columnCount: 3,
      openMoveSpeed: '200',
      openMoveEasing: '',
      closeMoveSpeed: '200',
      closeMoveEasing: 'easeOutElastic',
      spacing: '10px',
      openSpeed: '350',
      openEasing: 'easeOutElastic',
      closeSpeed: '100',
      closeEasing: '',
      scrollSpeed: '500',
      scrollEasing: 'easeInOutCirc',
      scrollTimmer: '300',
      alwaysAtSide: true,
      imgInOpacity: '1',
      imgOutOpacity: '0.7',
      xAxisSpacing: '20px',
      draggableBackSpeed: '500',
      draggableBackEasing: 'easeOutElastic',
      imgInSpeed: '200',
      imgOutSpeed: 'fast',
      imgInEasing: 'easeOutBack',
      imgOutEasing: 'easeOutElastic',
      imgEdge: {
        width: '32px',
        height: '32px'
      },
      scale: {
        width: '64px',
        height: '64px'
      },
      closeBasePosition: {
        top:'100px',
        left: '100px'
      },
      openBasePosition: {
        top: '-1px',
        left: '-1px'
      }
    },
    _create: function() {
      var that = this;
      that.obj = that.element;

      that.items = that._items ();

      that.options.columnCount = that.options.columnCount>0?that.options.columnCount:3;

      that.rowCount = parseInt (that.items.length/that.options.columnCount) + (that.items.length%that.options.columnCount?1:0);

      that._setOpenInfo ();

      that._setCloseInfo ();

      that.isClick = true;

      that.timer = null;

      that.isOpen = false;
    },
    _init: function () {
      var that = this;
      that.obj
          .empty ()
          .addClass ('OA-ui-BallMenu')
          .addClass ('close')
          .css ({
            'position': 'absolute',
            'opacity': 1,
            'top': parseFloat (that.closeInfo.top) + 'px',
            'left': parseFloat (that.closeInfo.left) + 'px',
            'width': parseFloat (that.closeInfo.width) + 'px',
            'height': parseFloat (that.closeInfo.height) + 'px'
          })
          .draggable ({
            distance: 20,
            scroll: false,
            containment: 'body',
            stop: function () {
              that.options.closeBasePosition.top = (parseFloat ($(this).css ('top')) - parseFloat ($(window).scrollTop())) + 'px';
              that.options.closeBasePosition.left = (parseFloat ($(this).css ('left')) - parseFloat ($(window).scrollLeft())) + 'px';

              that._setClosePosition (that.options.draggableBackSpeed, that.options.draggableBackEasing);
              that.isClick = false;
            }
          })
          .append (that._createDefaultItem ());

      if (!that.lockScanObj)
        that.lockScanObj = $('<div />')
                             .empty ()
                             .OA_LockScan ()
                             .click (function () {
                               that.close ();
                             })
                             .insertBefore(that.obj);

      $(window).scroll (function () {
        clearTimeout(that.timer);
        if (!that.lockScanObj.is(':visible')) {
          that.timer = setTimeout (function () { 
            that._setClosePosition (that.options.scrollSpeed, that.options.scrollEasing);
          }, that.options.scrollTimmer);
        }
      })
      .resize(function() {
        that._setClosePosition (that.options.scrollSpeed, that.options.scrollEasing);
      });
    },
    open: function () {
      var that = this;
      if (!that.isOpen) {
        that.lockScanObj.OA_LockScan ('open');
        that._setOpenInfo ();

        that.obj
            .empty ()
            .stop ()
            .animate ({
                top: (parseFloat (that.openInfo.top) + ((parseFloat (that.openInfo.height) - parseFloat (that.closeInfo.height)) / 2)) + 'px',
                left: (parseFloat (that.openInfo.left) + ((parseFloat (that.openInfo.width) - parseFloat (that.closeInfo.width)) / 2)) + 'px'
              },
              that.options.openMoveSpeed,
              that.options.openMoveEasing,
              function () {
                $(this)
                  .removeClass ('close')
                  .addClass ('open')
                  .animate ({
                    'top': parseFloat (that.openInfo.top) + 'px',
                    'left': parseFloat (that.openInfo.left) + 'px',
                    'width': parseFloat (that.openInfo.width) + 'px',
                    'height': parseFloat (that.openInfo.height) + 'px'
                  },
                  that.options.openSpeed,
                  that.options.openEasing,
                  function () {
                    that._createItems ().each (function () {
                      that.obj.append ($(this));
                    });
                  });
              }
            )
            .draggable({ disabled: true });

        that.isOpen = true;
      }
      return that.obj;
    },
    close: function () {
      var that = this;

      if (that.isOpen) {
        that.lockScanObj.OA_LockScan ('close');

        that.obj
            .empty ()
            .stop ()
            .animate ({
                'top': (parseFloat (that.openInfo.top) + ((parseFloat (that.openInfo.height) - parseFloat (that.closeInfo.height)) / 2)) + 'px',
                'left': (parseFloat (that.openInfo.left) + ((parseFloat (that.openInfo.width) - parseFloat (that.closeInfo.width)) / 2)) + 'px',
                'width': parseFloat (that.closeInfo.width) + 'px',
                'height': parseFloat (that.closeInfo.height) + 'px'
              },
              parseFloat (that.options.closeSpeed),
              that.options.closeEasing,
              function () {
                $(this)
                  .removeClass ('open')
                  .addClass ('close')
                  .append (that._createDefaultItem ());

                that._setClosePosition (that.options.closeMoveSpeed, that.options.closeMoveEasing);
              }
            )
            .draggable({ disabled: false });

        that.isOpen = false;
      }
      return that.obj;
    },
    getIsOpen: function () {
      var that = this;
      return that.isOpen
    },
    _setOpenInfo: function () {
      var that = this;
      
      w = (((parseFloat (that.options.imgEdge.width) + 2) * that.options.columnCount) + (parseFloat (that.options.spacing) * (that.options.columnCount - 1)) + (parseFloat (that.options.padding) * 2)) + 'px';
      h = (((parseFloat (that.options.imgEdge.height) + 2) * that.rowCount) + (parseFloat (that.options.spacing)*(that.rowCount - 1)) + (parseFloat (that.options.padding) * 2)) + 'px';
      
      that.options.openBasePosition.top = (parseFloat (that.options.openBasePosition.top) % parseFloat ($(window).height())) + 'px';
      that.options.openBasePosition.left = (parseFloat (that.options.openBasePosition.left) % parseFloat ($(window).width())) + 'px';
      
      t = ((parseFloat (that.options.openBasePosition.top)<0?((parseFloat ($(window).height()) - parseFloat (h)) / 2):((parseFloat (that.options.openBasePosition.top) - parseFloat (h)) / 2)) + parseFloat ($(window).scrollTop())) + 'px';
      l = ((parseFloat (that.options.openBasePosition.left)<0?((parseFloat ($(window).width()) - parseFloat (w))/2):((parseFloat (that.options.openBasePosition.left)-parseFloat (w))/2)) + parseFloat ($(window).scrollLeft())) + 'px';

      that.openInfo = {
        'top': t,
        'left': l,
        'width': w,
        'height': h
      };

      return that.obj;
    },
    _setCloseInfo: function () {
      var that = this;

      w = (parseFloat (that.options.imgEdge.width) + 2) + 'px';
      h = (parseFloat (that.options.imgEdge.height) + 2) + 'px';
      
      if (!that.options.alwaysAtSide) {
        t = ((parseFloat (that.options.closeBasePosition.top)<0?(0):((parseFloat (that.options.closeBasePosition.top) + parseFloat (h) + 2)>parseFloat ($(window).height())?(parseFloat ($(window).height()) - parseFloat (h) - 2):(parseFloat (that.options.closeBasePosition.top)))) + parseFloat ($(window).scrollTop ())) + 'px';
        l = ((parseFloat (that.options.closeBasePosition.left)<0?(0 + parseFloat (that.options.xAxisSpacing)):((parseFloat (that.options.closeBasePosition.left) + parseFloat (w) + 2)>parseFloat ($(window).width())?(parseFloat ($(window).width()) - parseFloat (w) - 2):(parseFloat (that.options.closeBasePosition.left)))) + parseFloat ($(window).scrollLeft ())) + 'px';
      } else {
        t = ((parseFloat (that.options.closeBasePosition.top)<0?(0):((parseFloat (that.options.closeBasePosition.top) + parseFloat (h) + 2)>parseFloat ($(window).height())?(parseFloat ($(window).height()) - parseFloat (h) - 2):(parseFloat (that.options.closeBasePosition.top)))) + parseFloat ($(window).scrollTop ())) + 'px';
        l = (((parseFloat (that.options.closeBasePosition.left) + (parseFloat(w) / 2))>(parseFloat ($(window).width()) / 2)?(parseFloat ($(window).width()) - parseFloat (w) - 2 - parseFloat (that.options.xAxisSpacing)):(0 + parseFloat (that.options.xAxisSpacing))) + parseFloat ($(window).scrollLeft())) + 'px';
      }
      
      that.closeInfo = {
        'top': t,
        'left': l,
        'width': w,
        'height': h
      };

      return that.obj;
    },
    _setClosePosition: function (speed, easing) {
      var that = this;

      that._setCloseInfo();

      that.obj
          .stop (true, true)
          .animate ({
              'top': parseFloat (that.closeInfo.top) + 'px',
              'left': parseFloat (that.closeInfo.left) + 'px'
            },
            speed,
            easing
          );

      return that.obj;
    },
    _createDefaultItem: function () {
      var that = this;

      return $('<img />')
               .attr ('src', that.obj.data ('src'))
               .attr ('title', that.obj.data ('name'))
               .addClass ('OA-ui-BallMenu-item')
               .css ({
                 'top': '0px',
                 'left': '0px',
                 'width': parseFloat (that.options.imgEdge.width) + 2 + 'px',
                 'height': parseFloat (that.options.imgEdge.height) + 2 + 'px'
               })
               .OA_Bubble ({
                 scale: that.options.scale,
                 inSpeed: that.options.imgInSpeed,
                 outSpeed: that.options.imgOutSpeed,
                 outOpacity: that.options.imgOutOpacity,
                 inOpacity: that.options.imgInOpacity,
                 inEasing: that.options.imgInEasing,
                 outEasing: that.options.imgOutEasing
               })
               .click (function () {
                 if (that.isClick ) {
                   that.open ();
                 }
               })
               .tooltip({
                    show: {
                      delay: 500,
                      effect: 'fade'
                    },
                    position: {
                      my: "center top",
                      at: "center bottom+20"
                    }
               })
              .mouseenter (function () {
                that.isClick = true;
              });
    },
    _items: function () {
      var that = this;
      return that.obj.children('input[type=hidden]').map (function () {
        if (typeof $(this).data ('src') !== 'undefined') {
          return {
            src: $(this).data ('src'),
            name: (typeof $(this).data ('name') === 'undefined'?'':$(this).data ('name')),
            href: (typeof $(this).data ('href') === 'undefined'?'':$(this).data ('href')),
            click_action: (typeof $(this).data ('click_action') === 'undefined'?'':$(this).data ('click_action'))
          };
        }
      });
    },
    _createItems: function () {
      var that = this;

      return that.items.map (function (index, item) {
        return $('<img />')
                 .attr ('src',item.src)
                 .attr ('title',item.name)
                 .addClass ('OA-ui-BallMenu-item')
                 .css ({
                   'top': (0 + parseFloat (that.options.padding) + (parseInt (index / that.options.columnCount) * (parseFloat (that.options.imgEdge.height) + 2 + parseFloat (that.options.spacing)))) + 'px',
                   'left': (0 + parseFloat (that.options.padding) + (parseFloat (index % that.options.columnCount) * (parseFloat (that.options.imgEdge.width) + 2 + parseFloat  (that.options.spacing)))) + 'px',
                   'width': parseFloat (that.options.imgEdge.width) + 2 + 'px',
                   'height': parseFloat (that.options.imgEdge.height) + 2 + 'px'
                 })
                 .click (function () {
                    eval (item.click_action);
                    if (item.href != '') {
                      window.location.assign (item.href);
                    }
                 })
                 .tooltip({
                      show: {
                        delay: 500,
                        effect: 'fade'
                      },
                      position: {
                        my: "center top",
                        at: "center bottom+20"
                      }
                 })
                 .OA_Bubble ({
                   scale: that.options.scale,
                   inSpeed: that.options.imgInSpeed,
                   outSpeed: that.options.imgOutSpeed,
                   outOpacity: that.options.imgOutOpacity,
                   inOpacity: that.options.imgInOpacity,
                   inEasing: that.options.imgInEasing,
                   outEasing: that.options.imgOutEasing
                 });
      });
    }
  });

  $.widget( "custom.OA_ComboBox", {
    options: {
      autoLoadFirst: false,
      isShowMatchKeyPoint: true,
      isMatcheGroup: true,
      noGroupName: 'other',
      showMatchKeyPointClass: 'OA-ui-OA_ComboBox-MatchPoint',
      isShowAllButton: true
    },
    _create: function() {
      var that = this;
      var obj = that.element;
      var input = $('<input />').attr ('id', obj.attr('id'));

      var data = obj.children("option" ).map (function () {
        var text = $( this ).text();
        var group = $( this ).data('group');
        return {text: text,
                group: group};
      });

      obj.replaceWith(input);
      input.wrap ($('<span>').addClass( "OA-ui-OA_ComboBox" ));
      var span = input.parent ('span');

      if (that.options.autoLoadFirst && data.length > 0) {
        input.val (data[0].text);
      }

      input.autocomplete ({
        delay: 0,
        minLength: 0,
        position: { my : "left top", at: "left bottom" },
        source: function(request, response) {
                  var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                  response( data.map(function(index, item) { 
                    var text = item.text;
                    var group = item.group;
                    var label = text.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(request.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), that.options.isShowMatchKeyPoint?"<strong class='" + that.options.showMatchKeyPointClass + "'>$1</strong>":"$1");

                    if ( ( !request.term || matcher.test(text) || (that.options.isMatcheGroup && matcher.test(group))) ) {
                      return {
                        label: label,
                        value: text,
                        group: group,
                        option: that
                      };
                    }
                  }) );
                }
      })
      .addClass ("ui-widget ui-widget-content ui-state-default ui-corner-left");

      input.data ("ui-autocomplete")._renderItem = function( ul, item ) {
        return $( "<li></li>" ).data( "item.autocomplete", item )
                               .append( "<a>" + item.label + "</a>" )
                               .appendTo( ul );
      };

      input.data ("ui-autocomplete")._renderMenu = function( ul, items ) {
        var groups = new Array();

        for (var i = 0, j = 0; i < items.length; i++) {
          if ( -1 == $.inArray (items[i].group, groups)) {
            groups[j++] = items[i].group;
          }
        }
        for (var i = 0; i < groups.length; i++) {
          var group = groups[i].replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex( input.val() ) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), that.options.isShowMatchKeyPoint?"<strong class='" + that.options.showMatchKeyPointClass + "'>$1</strong>":"$1");
          ul.append( "<li class='OA-ui-OA_ComboBox-AutocompleteCategory'>" + (typeof groups[i] === 'undefined' || groups[i] == "" ? that.options.noGroupName:group) + "</li>" );
          
          for (var j = 0; j < items.length; j++) {
            if (items[j].group == groups[i]) {
              this._renderItemData( ul, items[j] );
            }
          }
        }
      };
      if (that.options.isShowAllButton) {
        $( "<button> </button>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .appendTo(span)
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "OA-ui-OA_ComboBox-button ui-corner-right" )
          .click(function() {

            input.autocomplete("search", input.val());
            input.focus();
          });
      }
    }
  });

  $.widget( "custom.OA_Clock", {
    options: {
      isAutoStart: true,
      clockRadius: '100px',

      width: '300px',
      height: '300px',

      digList: [1,2,3,4,5,6,7,8,9,10,11,12],
      degreeSymbol: '.',

      secInfo: {
        symbol: '.',
        radius: '80px',
        symbolCount: 30
      },

      minInfo: {
        symbol: '.',
        radius: '70px',
        symbolCount: 20
      },

      hourInfo: {
        symbol: '.',
        radius: '60px',
        symbolCount: 15
      },

      timer: 50
    },
    _create: function() {
      var that = this;

      that.obj = that.element;

      that.centerPosition = {
        top: parseFloat (parseFloat (that.options.height) / 2) + 'px',
        left: parseFloat (parseFloat (that.options.width) / 2) + 'px'
      }
      
      that.isStart = false;

      that.timer = null;
    },
    _init: function () {
      var that = this;

      that.obj
          .addClass ('OA-ui-OA_Clock')
          .css ({
            'width': parseFloat (that.options.width) + 'px',
            'height': parseFloat (that.options.height) +'px'
          });
      
      that.dig = that._setDig ();

      that._goTime ();

      if (that.options.isAutoStart) {
        that.start ();
      }

    },
    _setDig: function () {
      var that = this;
      var dig;

      for (var i = 0; i < 60; i++) {

        var angle = parseFloat (parseFloat (parseFloat (90 - parseFloat (i * 6)) * Math.PI) / 180);
        
        if ((i % 5) == 0) {
          var digNum = parseInt (parseFloat (11 + parseInt (i / 5)) % 12);

          dig = $('<span>')
                  .text (that.options.digList[digNum])
                  .addClass ('OA-ui-OA_Clock-dig')
                  .appendTo (that.obj);

          dig.css ({
            'top': (parseFloat (that.centerPosition.top) + parseFloat (parseFloat (0 - parseFloat (dig.height())) / 2) + parseFloat (0 - parseFloat (parseFloat (that.options.clockRadius) * Math.sin(angle)))) + "px",
            'left': (parseFloat (that.centerPosition.left) + parseFloat (parseFloat (0 - parseFloat (dig.width())) / 2) + parseFloat (parseFloat (that.options.clockRadius) * Math.cos(angle))) + "px"
          });
        }
        else {
          $('<span>')
          .text (that.options.degreeSymbol)
          .addClass ('OA-ui-OA_Clock-degree')
          .css ({
            'top': (parseFloat (that.centerPosition.top) - parseFloat (parseFloat (dig.height()) / 2) + parseFloat (0 - parseFloat ((parseFloat (that.options.clockRadius) - (parseFloat (dig.height())/1.5)) * Math.sin(angle)))) + "px",
            'left': (parseFloat (that.centerPosition.left) + (parseFloat (parseFloat (that.options.clockRadius) - parseFloat (parseFloat (dig.width()) / 1.5)) * Math.cos(angle))) + "px"
          })
          .appendTo (that.obj);
        }
      }

      return dig;
    },
    _goTime: function () {
      var that = this;
      clearTimeout (that.timer);

      if (that.isStart) {

        that.obj.children ('.OA-ui-OA_Clock-sec').remove ();
        that.obj.children ('.OA-ui-OA_Clock-min').remove ();
        that.obj.children ('.OA-ui-OA_Clock-hour').remove ();

        var time = new Date ();

        that._setSec (time.getSeconds())
            ._setMin (time.getMinutes() + parseFloat (time.getSeconds() / 60))
            ._setHour (time.getHours() + parseFloat (time.getMinutes() / 60) + parseFloat (time.getSeconds() / 3600));
      }

      that.timer = setTimeout ($.proxy (that._goTime, that), parseFloat (that.options.timer));

      return that;
    },
    stop: function () {
      var that = this;
      that.isStart = false;
      return that.obj;
    },
    start: function () {
      var that = this;
      that.isStart = true;
      return that.obj;
    },
    _setSec: function (sec) {
      var that = this;

      for (var i = 0; i < that.options.secInfo.symbolCount; i++) {
        that._addPointer (i, parseFloat (((90 - sec * 6) * Math.PI) / 180), that.options.secInfo, 'sec').appendTo (that.obj);
      }

      return that;
    },
    _setMin: function (min) {
      var that = this;

      for (var i = 0; i < that.options.minInfo.symbolCount; i++) {
        that._addPointer (i, parseFloat (((90 - min * 6) * Math.PI) / 180), that.options.minInfo, 'min').appendTo (that.obj);
      }

      return that;
    },
    _setHour: function (hour) {
      var that = this;

      for (var i = 0; i < that.options.hourInfo.symbolCount; i++) {
        that._addPointer (i, parseFloat (((90 - hour * 5 * 6) * Math.PI) / 180), that.options.hourInfo, 'hour').appendTo (that.obj);
      }

      return that;
    },
    _addPointer: function (i, angle, info, unit) {
      var that = this;

      return $('<div />')
                .text (info.symbol)
                .addClass ('OA-ui-OA_Clock-' + unit)
                .css ({
                  'top': (parseFloat (that.centerPosition.top) + parseFloat (parseFloat (0 - parseFloat (that.dig.height())) / 2) + (parseFloat (parseFloat (info.radius) / parseFloat (info.symbolCount)) * i * Math.sin (0 - angle))) + "px",
                  'left': (parseFloat (that.centerPosition.left) + (parseFloat (parseFloat (info.radius) / parseFloat (info.symbolCount)) * i * Math.cos (angle))) + "px"
                });
    }
  });

  $.widget( "custom.OA_BarMenu", {
    options: {
      maxWidth: '100px',
      isShowImg: true,
      position: 'top',
      startHide: true,
      autoHide: true,
      xPadding: '100px',
      yPadding: '2px',
      barHideTimer: 1000,
      scrollTimmer: 300,
      topSpacing: '-1px',
      actionArea: false,
      itemIcon: {
        width: '32px',
        height: '32px'
      },
      extendButton: {
        top: '0px',
        left: '90%',
        width: '32px',
        height: '32px'
      },
      barOpen: {
        speed: '400',
        easing: 'easeOutBounce'
      },
      barClose: {
        speed: '300',
        easing: 'swing'
      },
      extendHide: {
        speed: '300',
        easing: 'swing'
      },
      extendShow: {
        speed: '500',
        easing: 'easeOutBounce'
      }
    },
    _create: function () {
      var that = this;
      that.obj = that.element;

      that.extendButton = that._getExtendButton ();

      that.dataObj = that._getDataObj (that._getDatas (that.obj), 1);
    },
    _init: function () {
      var that = this;

      that.obj
          .empty ()
          .removeAttr ('title')
          .addClass ('OA-ui-OA_BarMenu')
          .css ({
            'position': 'fixed',
            'display': 'inline',
            'width': '100%',
            'top': '0px',
            'left': '-1px',
            'padding': parseFloat (that.options.yPadding) + 'px ' + parseFloat (that.options.xPadding) + 'px',
          })
          .append (that.dataObj)
          .mouseenter (function () {
            clearTimeout (that.barHideTimer);
          })

      $(document).click(function(e) {
          var target = e.target;
          if (!$(target).is(that.obj) && !$(target).parents().is(that.obj) && !$(target).is(that.extendButton) && !$(target).parents().is(that.extendButton)) {
            that.barHideTimer = setTimeout ( $.proxy (that.close, that), that.options.barHideTimer);
          }
      });

      that.extendButton.insertBefore (that.obj);

      $(window).scroll (function () {
        clearTimeout(that.scrollTimer);
        if ($(window).scrollTop () > 0) {
          that.scrollTimer = setTimeout (function () {
            that.barHideTimer = setTimeout ( $.proxy (that.close, that), that.options.barHideTimer);
          }, that.options.scrollTimmer);
        }
      })
      .resize(function() {
        that.barHideTimer = setTimeout ( $.proxy (that.close, that), that.options.barHideTimer);
      })
      .mousemove(function(e) {
        if (that.options.actionArea) {
          var h = parseFloat (that.obj.height()) + parseFloat (that.obj.css ('padding-top')) + parseFloat (that.obj.css ('padding-bottom')) + parseFloat (that.obj.css ('border-top-width')) + parseFloat (that.obj.css ('border-bottom-width'))
          var y = (parseFloat(e.pageY)+ parseFloat (that.obj.css ('border-bottom-width'))-parseFloat ($(window).scrollTop())) % parseFloat ($(window).height());
          if (y < h && !that.obj.is (':visible')) {
            that.open ();
            clearTimeout (that.barHideTimer);
          }
        }
      });

      if (that.options.startHide) {
        that.close ();
      } else {
        that.open ();
      }

    },
    open: function () {
      var that = this;

      clearTimeout (that.barHideTimer);

      that._extendButtonHide ();

      that.obj.show ();
      that.obj.stop ().animate ({
        top: '-1px'
      }, parseFloat (that.options.barOpen.speed), that.options.barOpen.easing);

      return that.obj;
    },
    close: function () {
      var that = this;

      that.obj.stop ().animate ({
        top:0 - (parseFloat (that.obj.height()) + parseFloat (that.obj.css ('padding-top')) + parseFloat (that.obj.css ('padding-bottom')) + parseFloat (that.obj.css ('border-top-width')) + parseFloat (that.obj.css ('border-bottom-width'))) + 'px'
      }, parseFloat (that.options.barClose.speed), that.options.barClose.easing, function () {
        that.obj.hide ();
        that._extendButtonShow ();
      });

      return that.obj;
    },
    getIsOpen: function () {
      var that = this;

      return that.obj.is (':visible');
    },
    _getExtendButton: function () {
      var that = this;
      var src = that.obj.data ('src');
      var title = (that.obj.attr ('title') === undefined?(that.obj.data ('title') === undefined?that.obj.data ('title'):''):that.obj.attr ('title'));
      var img = $('<img />')
                  .empty ()
                  .attr ('title', title)
                  .attr ('src', src)
                  .css ({
                    'position': 'absolute',
                    'top': 0,
                    'left': 0,
                    'width': parseFloat (that.options.extendButton.width) + 'px',
                    'height': parseFloat (that.options.extendButton.height) + 'px',
                    'vertical-align':'middle'
                  });

      that._setExtendButtonInfo ();

      var extendButton = $('<div />')
                          .empty ()
                          .append (img)
                          .data ('isHide', true)
                          .addClass ('OA-ui-OA_BarMenu-extendButton')
                          .css ({
                            'position': 'fixed',
                            'display': 'inline-block',
                            'top': 0 - parseFloat (that.extendButtonInfo.height) - 2 + 'px',
                            'left': parseFloat (that.extendButtonInfo.left) + 'px',
                            'width': parseFloat (that.extendButtonInfo.width) + 'px',
                            'height': parseFloat (that.extendButtonInfo.height) + 'px',
                            'vertical-align':'middle'
                          })
                          .click (function () {
                            if (that.isClick) {
                              if ($(this).data ('isHide')) {
                                that.open ();
                              } else {
                                that.close ();
                              }
                            }
                            that.isClick = true;
                          }).draggable ({
                            scroll: false,
                            axis: "x",
                            distance: 20,
                            stop: function () {
                              that.options.extendButton.top = (parseFloat ($(this).css ('top')) - parseFloat ($(window).scrollTop())) + 'px';
                              that.options.extendButton.left = (parseFloat ($(this).css ('left')) - parseFloat ($(window).scrollLeft())) + 'px';

                              that._setExtendButtonPosition ();
                              that.isClick = false;
                            }
                          }).mouseenter (function () {
                            that.isClick = true;
                          });

      return extendButton;
    },
    _extendButtonShow: function () {
      var that = this;
      that._setExtendButtonPosition ();
      return that.extendButton;
    },
    _extendButtonHide: function () {
      var that = this;
      that.extendButton.stop ().animate ({
        'top': 0 - parseFloat (that.extendButtonInfo.height) - 2 + 'px',
        'left': parseFloat (that.extendButtonInfo.left) + 'px'
      }, parseFloat (that.options.extendHide.speed), that.options.extendHide.easing, function () {
        that.extendButton.hide ();
      });
      return that.extendButton;
    },
    _setExtendButtonInfo: function () {
      var that = this;

      w = (parseFloat (that.options.extendButton.width) + 2) + 'px';
      h = (parseFloat (that.options.extendButton.height) + 2) + 'px';
      
      t = (parseFloat (that.options.extendButton.top)<0?parseFloat (that.options.topSpacing):parseFloat (that.options.topSpacing))  + 'px';

      that.options.extendButton.left = (that.options.extendButton.left.length-1==(that.options.extendButton.left).lastIndexOf('%')?(parseFloat ($(window).width()) * parseFloat (parseFloat (that.options.extendButton.left) / 100)):parseFloat (that.options.extendButton.left)) + 'px';
      l = (parseFloat (that.options.extendButton.left)<0?0:(parseFloat (parseFloat (that.options.extendButton.left)+parseFloat (w)+2)>parseFloat ($(window).width())?parseFloat (parseFloat ($(window).width())-parseFloat (w)-2):parseFloat (that.options.extendButton.left))) + 'px'
      
      that.extendButtonInfo = {
        'top': t,
        'left': l,
        'width': w,
        'height': h
      };

      return that.obj;
    },
    _setExtendButtonPosition: function () {
      var that = this;

      if (!that.obj.is (':visible')) {
        that._setExtendButtonInfo ();

        if (!that.extendButton.is (':visible')) {
          that.extendButton.show ();
        }
        that.extendButton
          .stop ()
          .animate ({
            'top': parseFloat (that.extendButtonInfo.top) + 'px',
            'left': parseFloat (that.extendButtonInfo.left) + 'px'
          }, parseFloat (that.options.extendShow.speed), that.options.extendShow.easing);
      }
    },
    _getDataObj: function (list, level) {
      var that = this;

      var panelDiv = $('<div />')
                      .empty ()
                      .addClass (level==1?'OA-ui-OA_BarMenu-panel-level_1':'OA-ui-OA_BarMenu-panel')
                      .addClass (level==2?'OA-ui-OA_BarMenu-panel-arrow-level_2':(level>2?'OA-ui-OA_BarMenu-panel-arrow':''));

        $.each (list, function (index, item) {
          var type        = item.type;
          var text        = item.text;
          var html        = item.html;
          var title       = item.title;
          var src         = item.src;
          var href        = item.href;
          var click_action = item.click_action;
          var enable      = item.enable;
          var children    = item.children;

          var itemDiv = $('<div />')
                          .empty ()
                          .addClass (level==1?'OA-ui-OA_BarMenu-item-level_1':'OA-ui-OA_BarMenu-item')
                          .addClass (enable?'OA-ui-OA_BarMenu-item-enable':'')
                          .click (function () {
                            if (type!='folder'&&enable) {
                              eval (click_action);
                              if (href != '') {
                                window.location.assign (href);
                              }
                            }
                          })

          if (type == 'item' || type=='folder') {
            var img = (that.options.isShowImg&&src!=undefined?$('<img />')
                                                .empty ()
                                                .attr ('src', src)
                                                .addClass ('OA-ui-OA_BarMenu-item-img')
                                                .css ({
                                                  'width': parseFloat (that.options.itemIcon.width)+2+'px',
                                                  'height': parseFloat (that.options.itemIcon.height)+2+'px',
                                                }):null
                      );

            var content = (text == ''?null:$('<div/>')
                                            .empty ()
                                            .text (text)
                                            .attr ('title', title)
                                            .addClass ('OA-ui-OA_BarMenu-item-content')
                                            .css ({
                                              'max-width': that.options.maxWidth,
                                             // 'min-height': that.options.isShowImg?parseFloat (that.options.itemIcon.height):'autocomplete',
                                              'padding-left': (that.options.isShowImg&&src==undefined?parseFloat(that.options.itemIcon.width)+2:0),
                                            }));

            var extend = null;

            if (type=='folder'&&children.length&&enable) {
              var childrenDiv = that._getDataObj (children, level + 1).hide ();
              var timer = null;

              itemDiv.hover (function () {
                clearTimeout (timer);

                var top = 0;
                var left = 0;

                if (level==1) {
                  top = 0 + 3 + parseFloat (itemDiv.height()) + parseFloat (itemDiv.css ('padding-top')) + parseFloat (itemDiv.css ('padding-bottom'));
                  left = 0 - parseFloat (itemDiv.css ('border-top-width'));
                } else {
                  top = 0 - parseFloat (itemDiv.css ('border-top-width'));
                  left = 4 + parseFloat (itemDiv.width ()) + parseFloat (itemDiv.css ('padding-right')) + parseFloat (itemDiv.css ('padding-left'));
                }

                childrenDiv
                  .css ({
                    'top': top,
                    'left': left,
                    'z-index': level
                  }).show (level==1?'blind':'drop', 200);
                
              }, function () {
                timer = setTimeout (function () {childrenDiv.hide (level==1?'blind':'drop', 200);}, 1000);
                

              });

              extend = $('<div />')
                        .empty ()
                        .addClass ('OA-ui-OA_BarMenu-item-extend')
                        .addClass (level==1?'OA-ui-icon_white':'OA-ui-icon')
                        .addClass (level==1?'OA-ui-icon_00_04':'OA-ui-icon_00_02')
                        .css ({
                          'width': '16px',
                          'height': '16px',
                        });
            }

            itemDiv.append (img).append (content).append (extend).append (childrenDiv);

          } else if (type=='img'){
            var img = $('<img />')
                        .empty ()
                        .attr ('src', src)
                        .attr ('title', text)
                        .addClass ('OA-ui-OA_BarMenu-item-img');

            itemDiv.css ({
              'border-color': 'transparent'
            });
            itemDiv.append (img);
          } else if (type=='splitter') {
            itemDiv
              .html ('&nbsp;')
              .removeClass ('OA-ui-OA_BarMenu-item-enable')
              .addClass ('OA-ui-OA_BarMenu-item-splitter')
              .css ({
                'width': level==1?'0px':'auto',
                'height': level==1?'auto':'0px',
                'border-width':level==1?'0px 1px 0px 0px':'0px 0px 1px 0px'
              })
              .append (img);
          }
          itemDiv.appendTo (panelDiv);
        });

      return panelDiv;
    },
    _getDatas: function (obj) {
      var that = this;

      return obj.children ('div [data-type]').map (function () {
        var text = (typeof $(this).data ('text') === 'undefined'?'':$(this).data ('text'));
        var html  = $(this).data ('html');
        var title = (typeof $(this).data ('title') === 'undefined'?'':$(this).data ('title'));
        var type = (typeof $(this).data ('type') === 'undefined'?'item':$(this).data ('type'));
        var src  = (typeof $(this).data ('src') === 'undefined'?undefined:$(this).data ('src'));
        var href  = (typeof $(this).data ('href') === 'undefined'?'':$(this).data ('href'));
        var click_action  = (typeof $(this).data ('click_action') === 'undefined'?'':$(this).data ('click_action'));
        var enable  = (typeof $(this).data ('enable') === 'undefined'?true:$(this).data ('enable'));


        children = (type == 'folder'?that._getDatas ($(this)):undefined);
        
        return {
          text: text,
          html: html,
          title: title,
          type: type,
          src: src,
          href: href,
          click_action: click_action,
          enable: enable,
          children: children
        };
      });
    }
  });



  $.widget( "custom.OA_Calendar", {
    options: {
      dayWidth: '100px',
      dayMinHeight: '50px',
      weekTitle: ['日', '一', '二', '三', '四', '五', '六']
    },
    _create: function () {
      var that = this;
      that.obj = that.element;

      that._initFunctions ();
      
      that.datas = that._getDatas ();

    },
    _init: function () {
      var that = this;
      
      that.obj
      .empty ()
      .addClass ('OA_Calendar');

      
      that._initCalendar ('2013-09');
    },
    prevMonth: function () {
      var that = this;

      var year = parseInt (that.year);
      var month = parseInt (that.month);

      year = (month - 1)<1?(year - 1):year;
      month = (month + 12 - 2) % 12 + 1;

      that._initCalendar (year + '-' + month);
      
      return that.obj;
    },
    nextMonth: function () {
      var that = this;

      var year = parseInt (that.year);
      var month = parseInt (that.month);

      year = (month + 1)>12?(year + 1):year;
      month = (month) % 12 + 1;

      that._initCalendar (year + '-' + month);

      return that.obj;
    },
    goToMonth: function (start) {
      var that = this;
      that._initCalendar (start);
      return that.obj;
    },
    getYear: function () {
      var that = this;
      return that.year;
    },
    getMonth: function () {
      var that = this;
      return that.month;
    },
    _initCalendar: function (start) {
      var that = this;
      that.obj.empty ();

      if (!that.functions.checkDateFormat_ym (start)) {
        var today = new Date();
        start = today.getFullYear () + '-' + today.getMonth ();//((parseInt (today.getMonth ()) + 1 + '').length<2?'0':'') + (parseFloat (today.getMonth ()) + 1);
      }
      
      that.year = start.split ('-')[0];
      that.month = (parseInt(start.split ('-')[1])<10?'0':'') + parseInt (start.split ('-')[1]);

      var firstWeekDay = new Date(that.year, parseInt (that.month) - 1, 1).getDay()
      var DayCount = that.functions.getMonthDayCount (that.year, that.month);
      var weekCount = parseInt ((parseInt (firstWeekDay) + parseInt (DayCount)) / 7) + (parseInt ((parseInt (firstWeekDay) + parseInt (DayCount)) % 7)?1:0)

      var table = $('<table />').empty ().addClass ('OA_Calendar-table');
      var thead = $('<thead />').empty ().addClass ('OA_Calendar-table-thead');
      var tbody = $('<tbody />').empty ().addClass ('OA_Calendar-table-tbody');
      var thead_tr = $('<tr />').empty ().addClass ('OA_Calendar-table-thead-tr');
      
      $.each (that.options.weekTitle, function (i, t) {
        $('<th />').empty ().addClass ('OA_Calendar-table-thead-tr-th').text (''+t).appendTo (thead_tr);
      });

      for (var i = 0; i < weekCount; i++) {
        var tbody_tr = $('<tr />').empty ().addClass ('OA_Calendar-table-tbody-tr');
        for (var j = 0; j < 7; j++) {
          var day = parseInt (j + i * 7 - firstWeekDay + 1);
          var td = $('<td />').empty ().addClass ('OA_Calendar-table-tbody-tr-td');

          
          var dayDiv = $('<div />')
                          .empty ()
                          .addClass ('OA_Calendar-dayDiv')
                          .css ({
                          });
          if ((day < 1) || (day > DayCount)) {
            //$('<div />').addClass ('OA_Calendar-otherMonthDayDiv').text ('');
          } else {
            day = ((day+'').length<2?'0':'') + day

            $('<div />').addClass ('OA_Calendar-dayDiv-title')
                          .text (day)
                          .appendTo (dayDiv);

            ymd = that.year +'-'+ that.month+'-'+day;

            var datas = $.grep (that.datas, function (t, i) {
              return t.date == ymd;
            })


            if (datas.length) {
              $.each (datas[0].datas, function (i, t) {

                var img = $('<img />')
                            .empty ()
                            .addClass ('OA_Calendar-dayDiv-data-img')
                            .css ({
                              'max-width': parseFloat (that.options.dayWidth) - 2 + 'px'
                            })
                            .attr ('src', t.src);

                var title = $('<div />')
                            .empty ()
                            .addClass ('OA_Calendar-dayDiv-data-title')
                            .css ({
                              'max-width': parseFloat (that.options.dayWidth) - 2 + 'px'
                            })
                            .text (t.title);

                var content = $('<div />')
                            .empty ()
                            .addClass ('OA_Calendar-dayDiv-data-content')
                            .css ({
                              'max-width': parseFloat (that.options.dayWidth) - 2 + 'px'
                            })
                            .text (t.content);

                
                $('<div />').empty ()
                            .addClass ('OA_Calendar-dayDiv-data')
                            .css ({
                              'width': parseFloat (that.options.dayWidth) + 'px',
                              'min-height': parseFloat (that.options.dayMinHeight) + 'px'
                            })
                            .append (img)
                            .append (title)
                            .append (content)
                            .click (function () {
                              if (t.href != '') {
                                window.location.assign (t.href);
                              }
                            })
                            .appendTo (dayDiv);
              })
            } else {
              $('<div />').empty ()
                .addClass ('OA_Calendar-dayDiv-nodata')
                            .css ({
                              'width': parseFloat (that.options.dayWidth) + 'px',
                              'min-height': parseFloat (that.options.dayMinHeight) + 'px'
                            })
                .appendTo (dayDiv);
            }
          }
          dayDiv.appendTo (td);
          td.appendTo (tbody_tr);
        }
        tbody_tr.appendTo (tbody);
      }
      tbody.appendTo (table);

      thead_tr.appendTo (thead);
      thead.appendTo (table);
      table.appendTo (that.obj);
      return that.obj;
    },
    _getDatas: function () {
      var that = this;

      var sortDatas = function (list) {
        var dates = new Array ();

        for (var i = 0, j = 0; i < list.length; i++) {
          if ( -1 == $.inArray (list[i].date, dates)) {
            dates[j++] = list[i].date;
          }
        }

        return $.map (dates, function (t, i) {
                  
                  var datas = new Array ();

                  for (var i = 0, j = 0; i < list.length; i++) {
                    if (list[i].date == t) {
                      datas[j++] = { src: list[i].src,
                                      title: list[i].title,
                                      href: list[i].href,
                                      content: list[i].content
                                    }
                    }
                  }
                  return {date: t, datas: datas};
                });
      }

      return sortDatas (that.obj.children ('input[type=hidden][data-date]').map (function () {
                var date = (typeof $(this).data ('date') === 'undefined'?'':$(this).data ('date'));
                var src = (typeof $(this).data ('src') === 'undefined'?'':$(this).data ('src'));
                var href = (typeof $(this).data ('href') === 'undefined'?'':$(this).data ('href'));
                var title = (typeof $(this).data ('title') === 'undefined'?'':$(this).data ('title'));
                var content = (typeof $(this).data ('content') === 'undefined'?'':$(this).data ('content'));
                                
                if (that.functions.checkDateFormat_ymd (date)) {
                  return {
                    date: date,
                    src: src,
                    href: href,
                    title: title,
                    content: content
                  };
                }
              }));

    },
    _initFunctions: function () {
      var that = this;

      var checkDateFormat_ymd = function (date) {
                                  if (new RegExp(/\b\d{4}[\/-]\d{1,2}[\/-]\d{1,2}\b/).test(date)) {

                                    var year = parseInt (date.split ('-')[0]);
                                    var month = parseInt (date.split ('-')[1]);
                                    var day = parseInt (date.split ('-')[2]);
                                    if (checkDateFormat_ym (year + '-' + month) && day>0 && day<getMonthDayCount (year, month)+1) {
                                      return true;
                                    }
                                  }
                                  return false;
                                };

      var checkDateFormat_ym = function (date) {
                                  if (new RegExp(/\b\d{4}[\/-]\d{1,2}\b/).test(date)) {

                                    var year = parseInt (date.split ('-')[0]);
                                    var month = parseInt (date.split ('-')[1]);

                                    if (year>1899 && year<2101 && month>0 && month<13) {
                                      return true;
                                    }
                                  }
                                  return false;
                                };

      var getMonthDayCount = function (year, month) {
                                var month = parseInt (month) - 1;

                                var momths = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

                                if(month == 1) {
                                  return parseInt ((((parseInt (year) % 4) == 0) && ((parseInt (year) % 100) != 0) || ((parseInt (year) % 400) == 0))? 29: 28);

                                } else{
                                  return parseInt (momths[parseInt (month)]);
                                }
                              }
      return that.functions = {checkDateFormat_ymd: checkDateFormat_ymd,
                               checkDateFormat_ym: checkDateFormat_ym,
                               getMonthDayCount: getMonthDayCount
                             };
    }
  });

  $.widget ("custom.OA_HoverDir", {
    options: {
      titleTop: '70%',
      titleInspeed: 300,
      titleOutspeed: 300,
      textInspeed: 500,
      textOutspeed: 500,
      titleOutEasing: 'easeOutExpo',
      titleInEasing: 'easeOutExpo',
      textInEasing: 'easeOutQuint',
      textOutEasing: 'easeOutQuint',
      isBlur: true,
      titleBox: '.titleBox',
      textBox: '.textBox',
    },
    _create: function() {
      var that = this;
      that.obj = that.element;
      
      that.titleBoxHoverElem = that.obj.children (that.options.titleBox);
      that.textBoxHoverElem = that.obj.children (that.options.textBox);
    },
    _init: function () {
      var that = this;

      that.options.titleTop = $.trim (that.options.titleTop);
      that.options.titleTop = that.options.titleTop.slice(-1) == '%' ? that.options.titleTop : Math.abs (parseFloat (that.obj.height ()) - parseFloat (that.options.titleTop));
      
      that.titleBoxHoverElem.show ().css (that._getTitleBoxStyle ('show').to);

      that.obj.mouseenter (function (event) {
        var textBoxStyleCSS = that._getTextBoxStyle (that._getTextBoxDir ($(this), {x: event.pageX, y: event.pageY})),
            titleBoxStyleCSS = that._getTitleBoxStyle ('hide');

        if (that.options.isBlur) that.obj.children ().not ('div.titleBox, div.textBox').addClass ('OA_HoverDir-blur');
        that.titleBoxHoverElem.stop ().animate (titleBoxStyleCSS.to, that.options.titleOutspeed, that.options.titleOutEasing, function () {$(this).hide ();});
        that.textBoxHoverElem.stop ().hide ().css (textBoxStyleCSS.from).show ().animate (textBoxStyleCSS.to, that.options.textInspeed, that.options.textInEasing, function () {});
        
      }).mouseleave (function (event) {
        var textBoxStyleCSS = that._getTextBoxStyle (that._getTextBoxDir ($(this), {x: event.pageX, y: event.pageY})),
            titleBoxStyleCSS = that._getTitleBoxStyle ('show');

        if (that.options.isBlur) that.obj.children ().not ('div.titleBox, div.textBox').removeClass ('OA_HoverDir-blur');
        that.titleBoxHoverElem.stop ().show ().animate (titleBoxStyleCSS.to, that.options.titleInspeed, that.options.textInEasing);
        that.textBoxHoverElem.stop ().animate (textBoxStyleCSS.from, that.options.textOutspeed, that.options.textOutEasing, function () {$(this).hide ();});

      });
    },
    _getTextBoxDir: function ($el, coordinates) {
      var w = $el.width (),
          h = $el.height (),
          x = (coordinates.x - $el.offset().left - ( w/2 )) * ( w > h ? ( h/w ) : 1),
          y = (coordinates.y - $el.offset().top  - ( h/2 )) * ( h > w ? ( w/h ) : 1),
          direction = Math.round ((((Math.atan2 (y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
      return direction;
    },
    _applyAnimation : function(el, styleCSS, speed, easing, cellback) {
      var that = this,
          cellback = (typeof cellback === 'undefined') ? function (){}:cellback,
          easing   = (typeof easing   === 'undefined') ? that.options.easing:easing;

      $.fn.applyStyle = that.options.support ? $.fn.css : $.fn.animate;
      el.stop ().applyStyle (styleCSS, $.extend (true, [], {duration: speed + 'ms' }), easing, cellback);
    },
    _getTitleBoxStyle: function (action) {
      var that = this;
      switch (action) {
        case 'hide':
          fromStyle = {left: '0px', top: that.options.titleTop};
          toStyle = {left: '0px', top: '100%'};
          break;

        case 'show':
          fromStyle = {left: '0px', top: '100%'};
          toStyle = {left: '0px', top: that.options.titleTop};
          break;
      }
      return {from: fromStyle, to: toStyle};
    },
    _getTextBoxStyle: function (direction) {
      var fromStyle, toStyle,
          slideFromTop = {left: '0px', top: '-100%'},
          slideFromBottom = {left: '0px', top: '100%'},
          slideFromLeft = {left: '-100%', top: '0px'},
          slideFromRight = {left: '100%', top: '0px'},
          slideTop = {top: '0px'},
          slideLeft = {left: '0px'};
      
      switch (direction) {
        case 0:
          // from top
          fromStyle = !this.options.inverse ? slideFromTop : slideFromBottom;
          toStyle = slideTop;
          break;
        case 1:
          // from right
          fromStyle = !this.options.inverse ? slideFromRight : slideFromLeft;
          toStyle = slideLeft;
          break;
        case 2:
          // from bottom
          fromStyle = !this.options.inverse ? slideFromBottom : slideFromTop;
          toStyle = slideTop;
          break;
        case 3:
          // from left
          fromStyle = !this.options.inverse ? slideFromLeft : slideFromRight;
          toStyle = slideLeft;
          break;
      }
      return {from: fromStyle, to: toStyle};
    }
  });

  $.widget ( "custom.OA_SlideShow", {
    options: {
      fadeInTime: 550,
      fadeOutTime: 550,
    },
    _create: function() {
      var that = this;
      that.obj = that.element;
    },
    _init: function () {
      var that = this;

      that.imgs = that.obj.find ('img').map (function () { var data = {'title': $(this).attr ('title'), 'src': $(this).attr ('src')}; $(this).remove (); return data; });
      if (that.imgs.length) that.start (0);
    },
    start: function (i) {
      var that = this;
      if (that.obj.find ('img').length) that.obj.find ('img').eq (0).fadeOut (that.options.fadeOutTime, function () { that.obj.empty (); that.set (i); });
      else that.set (i);
      if (that.imgs.length > 1) setTimeout (function () { that.start (++i); }, 3000 + (Math.floor (Math.random () * (30)) * 100));
    },
    set: function (i) {
      var that = this;
      var img = that.imgs[i % that.imgs.length];
      $('<img />').attr ('src', img.src).appendTo (that.obj).hide ().fadeIn (that.options.fadeInTime);
      $('<div />').addClass ('text_label').text (img.title).appendTo (that.obj).hide ().fadeIn (that.options.fadeInTime);
    }
  });
})(jQuery);

/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 0.2.5
 *
 * http://jsfiddle.net/PVZB8/139/
 */
(function($) {

    jQuery.fn.extend({
        slimScroll: function(o) {

            var ops = o;
            //do it for every element that matches selector
            this.each(function(){

            var isOverPanel, isOverBar, isDragg, queueHide, barHeight,
                divS = '<div></div>',
                minBarHeight = 30,
                wheelStep = 30,
                o = ops || {},
                cwidth = o.width || 'auto',
                cheight = o.height || '250px',
                size = o.size || '7px',
                color = o.color || '#000',
                position = o.position || 'right',
                opacity = o.opacity || .4,
                alwaysVisible = o.alwaysVisible === true;
            
                //used in event handlers and for better minification
                var me = $(this);

                //wrap content
                var wrapper = $(divS).css({
                    position: 'relative',
                    overflow: 'hidden',
                    width: cwidth,
                    height: cheight
                }).attr({ 'class': 'slimScrollDiv' });

                //update style for the div
                me.css({
                    overflow: 'hidden',
                    width: cwidth,
                    height: cheight
                });

                //create scrollbar rail
                var rail  = $(divS).css({
                    width: '15px',
                    height: '100%',
                    position: 'absolute',
                    top: 0
                });

                //create scrollbar
                var bar = $(divS).attr({ 
                    'class': 'slimScrollBar ', 
                    style: 'border-radius: ' + size 
                    }).css({
                        background: color,
                        width: size,
                        position: 'absolute',
                        top: 0,
                        opacity: opacity,
                        display: alwaysVisible ? 'block' : 'none',
                        BorderRadius: size,
                        MozBorderRadius: size,
                        WebkitBorderRadius: size,
                        zIndex: 99
                });

                //set position
                var posCss = (position == 'right') ? { right: '1px' } : { left: '1px' };
                rail.css(posCss);
                bar.css(posCss);

                //wrap it
                me.wrap(wrapper);

                //append to parent div
                me.parent().append(bar);
                me.parent().append(rail);

                //make it draggable
                bar.draggable({ 
                    axis: 'y', 
                    containment: 'parent',
                    start: function() { isDragg = true; },
                    stop: function() { isDragg = false; hideBar(); },
                    drag: function(e) 
                    { 
                        //scroll content
                        scrollContent(0, $(this).position().top, false);
                    }
                });

                //on rail over
                rail.hover(function(){
                    showBar();
                }, function(){
                    hideBar();
                });

                //on bar over
                bar.hover(function(){
                    isOverBar = true;
                }, function(){
                    isOverBar = false;
                });

                //show on parent mouseover
                me.hover(function(){
                    isOverPanel = true;
                    showBar();
                    hideBar();
                }, function(){
                    isOverPanel = false;
                    hideBar();
                });

                var _onWheel = function(e)
                {
                    //use mouse wheel only when mouse is over
                    if (!isOverPanel) { return; }

                    var e = e || window.event;

                    var delta = 0;
                    if (e.wheelDelta) { delta = -e.wheelDelta/120; }
                    if (e.detail) { delta = e.detail / 3; }

                    //scroll content
                    scrollContent(0, delta, true);

                    //stop window scroll
                    if (e.preventDefault) { e.preventDefault(); }
                    e.returnValue = false;
                }

                var scrollContent = function(x, y, isWheel)
                {
                    var delta = y;

                    if (isWheel)
                    {
                        //move bar with mouse wheel
                        delta = bar.position().top + y * wheelStep;

                        //move bar, make sure it doesn't go out
                        delta = Math.max(delta, 0);
                        var maxTop = me.outerHeight() - bar.outerHeight();
                        delta = Math.min(delta, maxTop);

                        //scroll the scrollbar
                        bar.css({ top: delta + 'px' });
                    }

                    //calculate actual scroll amount
                    percentScroll = parseInt(bar.position().top) / (me.outerHeight() - bar.outerHeight());
                    delta = percentScroll * (me[0].scrollHeight - me.outerHeight());

                    //scroll content
                    me.scrollTop(delta);

                    //ensure bar is visible
                    showBar();
                }

                var attachWheel = function()
                {
                    if (window.addEventListener)
                    {
                        this.addEventListener('DOMMouseScroll', _onWheel, false );
                        this.addEventListener('mousewheel', _onWheel, false );
                    } 
                    else
                    {
                        document.attachEvent("onmousewheel", _onWheel)
                    }
                }

                //attach scroll events
                attachWheel();

                var getBarHeight = function()
                {
                    //calculate scrollbar height and make sure it is not too small
                    barHeight = Math.max((me.outerHeight() / me[0].scrollHeight) * me.outerHeight(), minBarHeight);
                    bar.css({ height: barHeight + 'px' });
                }

                //set up initial height
                getBarHeight();

                var showBar = function()
                {
                    //recalculate bar height
                    getBarHeight();
                    clearTimeout(queueHide);
                    
                    //show only when required
                    if(barHeight >= me.outerHeight()) {
                        return;
                    }
                    bar.fadeIn('fast');
                }

                var hideBar = function()
                {
                    //only hide when options allow it
                    if (!alwaysVisible)
                    {
                        queueHide = setTimeout(function(){
                            if (!isOverBar && !isDragg) { bar.fadeOut('slow'); }
                        }, 1000);
                    }
                }

            });
            
            //maintain chainability
            return this;
        }
    });

    jQuery.fn.extend({
        slimscroll: jQuery.fn.slimScroll
    });

})(jQuery);

