<div class="marquee-wrapper" id="honor-container" style="height: 25px; overflow: hidden; position: relative; background: transparent;">                            
    <div class="marquee-inner" id="honor-content">
        @foreach($honors as $honor)
            <span class="marquee-item" style="margin-right: 50px; display: inline-block;">
                🎉🎊 <a href="../posts/{{ $honor->id }}">{{ $honor->title }}</a>
            </span>
        @endforeach
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. 取得參數
    const behavior = "scroll";     // scroll, slide, alternate
    const direction = "up";        // left, right, up, down
    const amount = parseInt("2") || 6;

    const container = document.getElementById('honor-container');
    const content = document.getElementById('honor-content');

    if (!container || !content) return;

    // 2. 基礎樣式設定
    content.style.position = 'absolute';
    content.style.display = 'flex';
    content.style.whiteSpace = 'nowrap';
    
    if (direction === 'up' || direction === 'down') {
        content.style.flexDirection = 'column';
    }

    // 3. 動態計算動畫路徑
    const contentWidth = content.offsetWidth;
    const containerWidth = container.offsetWidth;
    const contentHeight = content.offsetHeight;
    const containerHeight = container.offsetHeight;

    // 定義動畫 Keyframes (唯一名稱：marqueeMoveHonor)
    let keyframes = '';
    if (direction === 'left') {
        keyframes = `@keyframes marqueeMoveHonor { 
            0% { transform: translateX(${containerWidth}px); } 
            100% { transform: translateX(-${contentWidth}px); } 
        }`;
    } else if (direction === 'right') {
        keyframes = `@keyframes marqueeMoveHonor { 
            0% { transform: translateX(-${contentWidth}px); } 
            100% { transform: translateX(${containerWidth}px); } 
        }`;
    } else if (direction === 'up') {
        keyframes = `@keyframes marqueeMoveHonor { 
            0% { transform: translateY(${containerHeight}px); } 
            100% { transform: translateY(-${contentHeight}px); } 
        }`;
    } else if (direction === 'down') {
        keyframes = `@keyframes marqueeMoveHonor { 
            0% { transform: translateY(-${contentHeight}px); } 
            100% { transform: translateY(${containerHeight}px); } 
        }`;
    }

    // 注入 CSS
    const style = document.createElement('style');
    style.innerHTML = keyframes;
    document.head.appendChild(style);

    // 4. 套用動畫效果
    const duration = (direction === 'left' || direction === 'right') 
                    ? (contentWidth + containerWidth) / (amount * 10) 
                    : (contentHeight + containerHeight) / (amount * 5);

    content.style.animation = `marqueeMoveHonor ${duration}s linear infinite`;

    // 5. 處理 Behavior
    if (behavior === 'slide') {
        content.style.animationIterationCount = '1';
        content.style.animationFillMode = 'forwards';
    } else if (behavior === 'alternate') {
        content.style.animationDirection = 'alternate';
    }

    // 滑鼠移入停止
    container.onmouseover = () => content.style.animationPlayState = 'paused';
    container.onmouseout = () => content.style.animationPlayState = 'running';
});
</script>
