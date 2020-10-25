/**
 * Created by rockyren on 14/12/25.
 */
define(['mindmapJS/imp/otherModule/DataHelper', 'mindmapJS/imp/renderModule/nodeShapeRelative'], function(DataHelper, nodeShapeRelative){
  /**
   * 子结点重绘的策略工厂
   * @type {Function}
   */
  var ChildrenRenderFactory = (function(){
    return {
      createRenderStrategy: function(node){
        var strategy;
        //如果结点是根结点,则实现第一层结点添加算法
        if(node.isRootNode()){
          strategy = new ChildrenRenderStrategy(new FirstRender());
        }else{
          strategy = new ChildrenRenderStrategy(new NormalRender());
        }
        return strategy;

      }
    };
  }());

  /**
   * 子结点重绘策略类
   * @param strategy
   * @constructor
   */
  function ChildrenRenderStrategy(strategy){
    this.strategy = strategy;
  }
  ChildrenRenderStrategy.prototype.reRenderChildrenNode = function(node){
    this.strategy.doRender(node);
  };

  /**
   * 抽象子结点重绘类
   * @constructor
   */
  function AbstractRender() {}

  AbstractRender.prototype.commonRender = function(father, children, direction){
    //获取父结点的中间坐标
    var hfx = father.x + nodeShapeRelative.getSingleNodeWidth(father)/2;
    var hfy = father.y + nodeShapeRelative.getSingleNodeHeight(father)/2;


    var childrenAreaHeight = 0,     //结点的所有子结点所占区域的高度
        startY,                     //子节点区域的起始高度
        childX,                     //子节点x坐标
        childY;                     //子节点的y坐标

    childX = hfx + direction * (nodeShapeRelative.nodeXInterval + nodeShapeRelative.getSingleNodeWidth(father)/2);


    DataHelper.forEach(children, function(child){
      //通过结点的areHeight属性保存结点高度
      child.areaHeight = nodeShapeRelative.getNodeAreaHeight(child);
      childrenAreaHeight += child.areaHeight;
    });

    startY = hfy - childrenAreaHeight/2;

    DataHelper.forEach(children, function(child){
      //计算子结点y坐标
      childY = startY + child.areaHeight/2 - nodeShapeRelative.getSingleNodeHeight(child)/2;
      //起始高度累加
      startY += child.areaHeight;


      //如果子结点仍未渲染,则渲染之
      if(!child.shape){
        child.x = childX;
        child.y = childY;
        child.gRenderer.drawNode(child);
        //左边结点需左移一个结点宽度
        if(direction === -1){
          child.translate(-nodeShapeRelative.getSingleNodeWidth(child), 0);
        }
      }
      //如果子结点已经渲染,则y轴平移
      else{
        var dy = childY - child.y;
        child.translate(0, dy);
      }
    });
  };

  AbstractRender.prototype.doRender = function(node){
    console.log('该方法应该被覆盖');
  };

  /**
   * 第一层子结点渲染类
   * @constructor
   */
  function FirstRender(){}
  FirstRender.prototype = new AbstractRender();
  FirstRender.prototype.constructor = FirstRender;

  FirstRender.prototype.doRender = function(node){
    var children = this.getDirectionChildren(node);
    this.commonRender(node, children.leftChildren, -1);
    this.commonRender(node, children.rightChildren, 1);
  };

  //根据子结点的direction取得左右子结点集合
  FirstRender.prototype.getDirectionChildren = function(node){
    var leftChildren = {},
        rightChildren = {};
    DataHelper.forEach(node.children, function(child){
      if(child.direction == -1){
        leftChildren[child.id] = child;
      }else{
        rightChildren[child.id] = child;
      }
    });

    return {
      leftChildren: leftChildren,
      rightChildren: rightChildren
    };
  };

  /**
   * 一般子结点渲染类
   * @constructor
   */
  function NormalRender(){}
  NormalRender.prototype = new AbstractRender();
  NormalRender.prototype.constructor = NormalRender;

  NormalRender.prototype.doRender = function(node){
    this.commonRender(node, node.children, node.direction);
  };


  return ChildrenRenderFactory;



});