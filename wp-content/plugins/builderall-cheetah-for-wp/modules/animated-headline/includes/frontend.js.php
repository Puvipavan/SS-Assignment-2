<?php
$node_id = "ba-node-$id";
?>

(function ($) {

  var phrasesConfig = [];

    <?php foreach ($settings->text_items as $key => $item) :?>
  phrasesConfig.push('<?php echo str_replace("'", "\'", $item->text);?>')
    <?php endforeach;  ?>


  var config = {
    autoStart: true,
    phrases: phrasesConfig,
    in: '<?php echo $settings->effect_in?>',
    out: '<?php echo $settings->effect_out?>',
    duration: <?php echo $settings->duration * 100?>,
    delay: <?php echo $settings->delay * 1000?>,
    repeat: '<?php echo $settings->loop ? 'infinite' : null?>',
    color: '<?php echo BACheetahColor::hex_or_rgb($settings->color) ?>'
  }


  var Publish_WordEffect = function (config) {

    var _container;
    var _phrases = [];
    var _self = this;
    var _index = 1;
    var _started = false;
    var _timeout = {};
    var _repeat;
    var heightOfPhrase = 0;

    _container = $('<div>')
      .addClass('word_effect');

    function _buildPhrases() {

      for (var i = 0; i < config.phrases.length; i++) {


        var content = $('<div>')
          .addClass('word-effect-phrase')
          .appendTo(_container);

        var table = $('<div>')
          .addClass('word-effect-table')
          .appendTo(content);

        var cell = $('<div>')
          .addClass('word-effect-cell')
          .appendTo(table);

        var data = {
          node: cell,
          pieces: []
        };

        for (var j = 0; j < config.phrases[i].length; j++) {
          data.pieces.push(
            $('<span>')
              .css({
                color: config.color
              })
              .addClass(i == 0 ? 'in visible' : '')
              .html(config.phrases[i].charAt(j) == ' ' ? '&ensp;' : config.phrases[i].charAt(j))
              .appendTo(cell)
          );
        }

        _phrases.push(data);

      }
    }

    function _getHeightPhrases() {

      var collection = document.getElementsByClassName("word-effect-phrase")
      heightOfPhrase = 0;

      for (var i = 0; i < collection.length; i++) {
        if (collection[i].offsetHeight > heightOfPhrase)
          heightOfPhrase = collection[i].offsetHeight;
      }

      _container.height(heightOfPhrase);
    }

    this.config = function (attr, value) {
      if (typeof value != 'undefined') {
        config[attr] = value;
      } else {
        return config[attr];
      }
    };

    function _addClass(phrase, position, type, onComplete) {

      if (position < phrase.pieces.length) {

        phrase.pieces[position]
          .removeClass()
          .addClass(type + ' ' + config[type])
          .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
            $(this)
              .addClass(type == 'in' ? 'visible' : 'invisible');
          });
        ;

        _timeout[type] = setTimeout(function () {
          position++;
          _addClass(phrase, position, type, onComplete);
          _getHeightPhrases();
        }, config.duration);

      } else {
        if (typeof onComplete == 'function') {
          onComplete();
        }
      }
    }

    function _animate() {

      if (_repeat == 'infinite' || _repeat > 0) {

        _timeout.animation = setTimeout(function () {
          if (typeof _repeat == 'number' && _index + 1 == _phrases.length) {
            _repeat--;
          }

          var prev = (_index == 0 ? _phrases.length - 1 : _index - 1);
          var next = (_index + 1 == _phrases.length ? 0 : _index + 1);

          var count = 0;

          function onComplete() {
            count++;
            if (count == 2) {
              _index = next;
              _animate();
            }
          }

          _addClass(_phrases[prev], 0, 'out', onComplete);
          _addClass(_phrases[_index], 0, 'in', onComplete);

        }, config.delay);

      } else {
        _self.reset();
      }

    };

    function _clearTimeout() {
      for (var i in _timeout) {
        clearTimeout(_timeout[i]);
      }
      _timeout = {};
    }

    this.reset = function () {
      _repeat = config.repeat;
      _started = false;
      _clearTimeout();
    };

    this.build = function () {
      _self.reset();
      _index = 1;
      _phrases = [];
      _container.empty();
      _buildPhrases();
      _self.start();
      _container.appendTo(document.querySelector('.ba-cheetah-node-<?php echo $node_id; ?>'))
      _getHeightPhrases();
    };

    this.start = function () {
      if (!_started) {
        _animate();
        _started = true;
      }
    };

    _self.build();
  };

  Publish_WordEffect(config);

})(jQuery);
